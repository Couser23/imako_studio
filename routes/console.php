<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $nowTime = Carbon::now()->format('H:i:s');
    $today = Carbon::today();

    // 1. Confirmed -> In Progress
    Booking::where('status', 'confirmed')
        ->whereDate('booking_date', $today)
        ->whereTime('start_time', '<=', $nowTime)
        ->update(['status' => 'in_progress']);

    // 2. In Progress -> Completed
    Booking::where('status', 'in_progress')
        ->whereDate('booking_date', $today)
        ->whereTime('end_time', '<=', $nowTime)
        ->update(['status' => 'completed']);
        
})->everyMinute();

Schedule::command('notifications:run-scheduled')->everyMinute();
Schedule::command('reports:generate')->everyMinute();
Schedule::command('users:clean-unverified')->everyMinute();
