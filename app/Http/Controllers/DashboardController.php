<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
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

        return view('dashboard.index', 
            compact(
                'pendingGuests', 'acceptedGuests', 'rejectedGuests', 
                'dispositionGuests', 'rescheduleGuests', 'countNotulensi', 
                'yesCount', 'noCount', 'companionNames', 
                'companionCounts'
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
