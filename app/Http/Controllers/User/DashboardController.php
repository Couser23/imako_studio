<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $activeBooking = $user->bookings()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with('package')
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first();

        $packages = \App\Models\Package::where('is_active', true)->take(4)->get();

        $latestResult = $user->bookings()
            ->where('status', 'completed')
            ->whereNotNull('result_link')
            ->with('package')
            ->orderBy('updated_at', 'desc')
            ->first();

        $recentBookings = $user->bookings()
            ->with('package', 'payments')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('user.dashboard', compact('activeBooking', 'packages', 'latestResult', 'recentBookings'));
    }
}
