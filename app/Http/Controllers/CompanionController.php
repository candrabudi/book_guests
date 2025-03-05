<?php

namespace App\Http\Controllers;

use App\Models\Companion;
use Illuminate\Http\Request;

class CompanionController extends Controller
{
    public function index()
    {
        return view('companions.index');
    }
    
    public function list()
    {
        $companions = Companion::all();
        return response()->json(['html' => view('companions.partials.companions', compact('companions'))->render()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'companion_name' => 'required|string|max:255',
        ]);

        Companion::create($request->only('companion_name'));

        return response()->json(['success' => 'Companion created successfully.']);
    }

    public function edit(Companion $companion)
    {
        return response()->json($companion);
    }

    public function update(Request $request, Companion $companion)
    {
        $request->validate([
            'companion_name' => 'required|string|max:255',
        ]);

        $companion->update($request->only('companion_name'));

        return response()->json(['success' => 'Companion updated successfully.']);
    }

    public function destroy(Companion $companion)
    {
        $companion->delete();

        return response()->json(['success' => 'Companion deleted successfully.']);
    }
}
