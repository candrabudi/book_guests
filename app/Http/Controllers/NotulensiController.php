<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notulensi;

class NotulensiController extends Controller
{
    public function index()
    {
        $notulensis = Notulensi::orderBy('created_at', 'DESC')
            ->get();
        return view('notulensis.index', compact('notulensis'));
    }
}
