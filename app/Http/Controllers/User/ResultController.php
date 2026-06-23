<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $results = auth()->user()->bookings()
            ->with(['package', 'assignments.employee'])
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('user.results.index', compact('results'));
    }
}
