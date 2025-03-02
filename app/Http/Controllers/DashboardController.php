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
        $pendingGuests = Guest::whereDate('created_at', $dateNow)
            ->where('status', 'pending')
            ->count();

        $acceptedGuests = Guest::whereDate('created_at', $dateNow)
            ->where('status', 'accepted')
            ->count();

        $rejectedGuests = Guest::whereDate('created_at', $dateNow)
            ->where('status', 'rejected')
            ->count();

        $dispositionGuests = Guest::whereDate('created_at', $dateNow)
            ->where('status', 'disposition')
            ->count();
        
        $rescheduleGuests = Guest::whereDate('created_at', $dateNow)
            ->where('status', 'reschedule')
            ->count();
        
        $countNotulensi = Notulensi::whereDate('created_at', $dateNow)
            ->count();


        $yesCount = Guest::where('appointment', 'yes')->count();
        $noCount = Guest::where('appointment', 'no')->count();

        $companions = Companion::withCount('guests')
            ->orderBy('guests_count', 'desc')
            ->limit(10)
            ->get();
        $companionNames = $companions->pluck('companion_name');
        $companionCounts = $companions->pluck('guests_count');


        $query = Identity::withCount('guests')->with('guests.institution');

        $dataVisitors = $query->get();

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

        $dataVisitors->transform(function ($visitor) use (&$totalSimilarData, &$totalVisits, &$chartData) {
        $isSimilarData = false;
        $similarityLabel = [];
        
        $similarNameDifferentNik = Identity::where('full_name', $visitor->full_name)
            ->where('nik', '!=', $visitor->nik)
            ->exists();

        if ($similarNameDifferentNik) {
            $isSimilarData = true;
            $similarityLabel[] = 'Nama mirip, NIK berbeda';
        }

        $similarInstitution = Guest::where('institution_id', function ($subQuery) use ($visitor) {
                $subQuery->select('institution_id')
                        ->from('guests')
                        ->where('identity_id', $visitor->id)
                        ->limit(1);
            })
            ->where('identity_id', '!=', $visitor->id)
            ->exists();

        if ($similarInstitution) {
            $isSimilarData = true;
            $similarityLabel[] = 'Institusi sama, NIK berbeda';
        }

        $visitCount = $visitor->guests_count;
        $visitCategory = '';

        // Kategori kunjungan
        if ($visitCount == 1) {
            $visitCategory = '1x';
            $chartData['visitCategories']['1x']++;
        } elseif ($visitCount > 1 && $visitCount <= 3) {
            $visitCategory = 'more_than_1x';
            $chartData['visitCategories']['more_than_1x']++;
        } elseif ($visitCount > 3 && $visitCount <= 5) {
            $visitCategory = 'more_than_3x';
            $chartData['visitCategories']['more_than_3x']++;
        } elseif ($visitCount > 5 && $visitCount <= 10) {
            $visitCategory = 'more_than_5x';
            $chartData['visitCategories']['more_than_5x']++;
        } elseif ($visitCount > 10) {
            $visitCategory = 'more_than_10x';
            $chartData['visitCategories']['more_than_10x']++;
        }

        if ($isSimilarData) {
            $totalSimilarData++;
        }
        $totalVisits += $visitCount;

        return [
                'nik' => $visitor->nik,
                'full_name' => $visitor->full_name,
                'guests_count' => $visitCount,
                'visit_category' => $visitCategory,
                'is_similar_data' => $isSimilarData,
                'similarity_label' => $similarityLabel,
            ];
        });

        $similarityPercentage = ($totalSimilarData / $totalVisitors) * 100;
        $averageVisits = $totalVisits / $totalVisitors;

    
        return view('dashboard.index', 
            compact(
                'pendingGuests', 'acceptedGuests', 'rejectedGuests', 
                'dispositionGuests', 'rescheduleGuests', 'countNotulensi', 
                'yesCount', 'noCount', 'companionNames', 
                'companionCounts', 'dataVisitors', 'chartData', 
                'similarityPercentage', 'averageVisits', 'totalVisitors'
            )
        );
    }

    public function getGuestComparison()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $todayData = Guest::select('status', \DB::raw('count(*) as total'))
            ->whereDate('created_at', $today)
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();
        $yesterdayData = Guest::select('status', \DB::raw('count(*) as total'))
            ->whereDate('created_at', $yesterday)
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();
        return response()->json([
            'today' => $todayData,
            'yesterday' => $yesterdayData
        ]);
    }
}
