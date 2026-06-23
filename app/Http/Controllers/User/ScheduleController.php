<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $userSchedules = \App\Models\Booking::with(['package', 'addons'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'package_name' => $booking->package->name ?? 'Paket Kustom',
                    'booking_date' => \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d'),
                    'start_time' => \Carbon\Carbon::parse($booking->start_time)->format('H:i'),
                    'end_time' => \Carbon\Carbon::parse($booking->end_time)->format('H:i'),
                    'status' => $booking->status,
                ];
            });

        return view('user.schedules.index', compact('userSchedules'));
    }
}
