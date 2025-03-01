<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
class HistoryGuestController extends Controller
{
    public function index()
    {
        $guests = Guest::orderBy('created_at', 'DESC')
            ->get();

        
        return view('history_guests.index', compact('guests'));
    }

    public function getHistoryGuests(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
    
        $pendingGuests = Guest::join('identities as idt', 'idt.id', '=', 'guests.identity_id')
            ->join('institutions as inst', 'inst.id', '=', 'guests.institution_id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('idt.full_name', 'like', "%{$search}%")
                        ->orWhere('idt.phone_number', 'like', "%{$search}%")
                        ->orWhere('inst.institution_name', 'like', "%{$search}%");
                });
            })
            ->select('guests.*', 'idt.nik', 'idt.full_name', 'inst.institution_name')
            ->orderBy('guests.created_at', 'DESC')
            ->paginate($perPage);
    
        return response()->json($pendingGuests);
    }
    

}
