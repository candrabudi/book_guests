<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Identity;
class VisitorController extends Controller
{
    public function index()
    {
        $query = Identity::select('nik', 'full_name')
                        ->withCount('guests')
                        ->groupBy('nik', 'full_name', 'id'); 

        $visitors = $query->get();

        $oneVisit = 0;
        $moreThanOneVisit = 0;
        $moreThanThreeVisit = 0;
        $moreThanFiveVisit = 0;

        foreach ($visitors as $visitor) {
            $guestCount = $visitor->guests_count;

            if ($guestCount == 1) {
                $oneVisit++;
            } elseif ($guestCount > 1 && $guestCount <= 3) {
                $moreThanOneVisit++;
            } elseif ($guestCount > 3 && $guestCount <= 5) {
                $moreThanThreeVisit++;
            } elseif ($guestCount > 5) {
                $moreThanFiveVisit++;
            }
        }

        $responseData = [
            'total_visitors' => $visitors->count(),
            'one_visit' => $oneVisit,
            'more_than_one_visit' => $moreThanOneVisit,
            'more_than_three_visit' => $moreThanThreeVisit,
            'more_than_five_visit' => $moreThanFiveVisit,
        ];

        return view('visitors.index', compact('responseData'));
    }

    public function list(Request $request)
    {
        $search = $request->input('search');

        $query = Identity::select('nik', 'full_name')
                        ->withCount('guests') 
                        ->groupBy('nik', 'full_name', 'id'); 

        if ($search) {
            $query->where('nik', 'like', '%' . $search . '%')
                ->orWhere('full_name', 'like', '%' . $search . '%');
        }

        $dataVisitors = $query->paginate(10);

        return response()->json($dataVisitors);
    }
}
