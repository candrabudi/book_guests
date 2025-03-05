<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Identity;
use App\Models\Institution;
use App\Models\Notulensi;
use App\Models\Companion;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $guestStatuses = Guest::select('status', \DB::raw('count(*) as total'))
            ->whereDate('created_at', $dateNow)
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts = [
            'pendingGuests' => $guestStatuses['pending'] ?? 0,
            'acceptedGuests' => $guestStatuses['accepted'] ?? 0,
            'rejectedGuests' => $guestStatuses['rejected'] ?? 0,
            'dispositionGuests' => $guestStatuses['disposition'] ?? 0,
            'rescheduleGuests' => $guestStatuses['reschedule'] ?? 0,
            'completedGuests' => $guestStatuses['completed'] ?? 0
        ];

        $countNotulensi = Notulensi::whereDate('created_at', $dateNow)->count();
        $yesCount = Guest::where('appointment', 'yes')->count();
        $noCount = Guest::where('appointment', 'no')->count();

        $companions = Companion::withCount('guests')
            ->orderBy('guests_count', 'desc')
            ->limit(10)
            ->get();
        $companionNames = $companions->pluck('companion_name');
        $companionCounts = $companions->pluck('guests_count');

        // Visitor data
        $dataVisitors = Identity::withCount('guests')
            ->with('guests.institution')
            ->get();

        $totalVisitors = $dataVisitors->count();
        $totalSimilarData = 0;
        $totalVisits = 0;

        $chartData = [
            'visitCategories' => [
                '1x' => 0,
                'more_than_1x' => 0,
                'more_than_3x' => 0,
                'more_than_5x' => 0,
                'more_than_10x' => 0
            ]
        ];

        foreach ($dataVisitors as $visitor) {
            $isSimilarData = false;
            $similarityLabel = [];

            $similarNameDifferentNik = Identity::where('full_name', $visitor->full_name)
                ->where('phone_number', '!=', $visitor->nik)
                ->exists();

            $similarInstitution = Guest::where('institution_id', $visitor->guests->first()->institution_id ?? null)
                ->where('identity_id', '!=', $visitor->id)
                ->exists();

            if ($similarNameDifferentNik || $similarInstitution) {
                $isSimilarData = true;
                if ($similarNameDifferentNik) {
                    $similarityLabel[] = 'Nama mirip, Nomor Handphone berbeda';
                }
                if ($similarInstitution) {
                    $similarityLabel[] = 'Institusi sama, Nomor Handphone berbeda';
                }
                $totalSimilarData++;
            }

            $visitCount = $visitor->guests_count;
            $visitCategory = $this->categorizeVisit($visitCount, $chartData);
            $totalVisits += $visitCount;

            $visitor->is_similar_data = $isSimilarData;
            $visitor->similarity_label = $similarityLabel;
            $visitor->visit_category = $visitCategory;
        }

        $similarityPercentage = ($totalSimilarData / $totalVisitors) * 100;
        $averageVisits = $totalVisits / $totalVisitors;

        return view('dashboard.index', compact(
            'statusCounts', 'countNotulensi', 'yesCount', 'noCount',
            'companionNames', 'companionCounts', 'dataVisitors',
            'chartData', 'similarityPercentage', 'averageVisits', 'totalVisitors'
        ));
    }

    private function categorizeVisit($visitCount, &$chartData)
    {
        if ($visitCount == 1) {
            $chartData['visitCategories']['1x']++;
            return '1x';
        } elseif ($visitCount > 1 && $visitCount <= 3) {
            $chartData['visitCategories']['more_than_1x']++;
            return 'more_than_1x';
        } elseif ($visitCount > 3 && $visitCount <= 5) {
            $chartData['visitCategories']['more_than_3x']++;
            return 'more_than_3x';
        } elseif ($visitCount > 5 && $visitCount <= 10) {
            $chartData['visitCategories']['more_than_5x']++;
            return 'more_than_5x';
        } else {
            $chartData['visitCategories']['more_than_10x']++;
            return 'more_than_10x';
        }
    }

    public function getGuestComparison()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayData = $this->getGuestStatusByDate($today);
        $yesterdayData = $this->getGuestStatusByDate($yesterday);

        return response()->json([
            'today' => $todayData,
            'yesterday' => $yesterdayData
        ]);
    }

    private function getGuestStatusByDate($date)
    {
        return Guest::select('status', \DB::raw('count(*) as total'))
            ->whereDate('created_at', $date)
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }


}
