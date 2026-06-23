<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Assignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeScheduleController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->query('view', 'harian');
        $dateStr = $request->query('date', Carbon::today()->toDateString());
        $currentDate = Carbon::parse($dateStr);
        
        $allEmployees = User::where('role', 'pegawai')->get();
        
        $employees = $allEmployees;
        if ($request->has('employee_id') && $request->employee_id != '') {
            $employees = clone $allEmployees;
            $employees = $allEmployees->where('id', $request->employee_id);
        }

        $employees->load(['leaveRequests' => function($q) use ($currentDate) {
            $q->where('status', 'approved')
              ->whereDate('start_date', '<=', $currentDate)
              ->whereDate('end_date', '>=', $currentDate);
        }]);

        $todayAssignments = Assignment::whereHas('booking', function ($query) use ($currentDate) {
            $query->whereDate('booking_date', $currentDate);
        })->count();
        
        $ongoingAssignments = Assignment::whereHas('booking', function ($query) use ($currentDate) {
            $query->whereDate('booking_date', $currentDate)
                  ->whereTime('start_time', '<=', Carbon::now())
                  ->whereTime('end_time', '>=', Carbon::now());
        })->count();

        // Stats tracking
        $stats = [
            'total_employees' => $allEmployees->count(),
            'today_assignments' => $todayAssignments,
            'ongoing' => $ongoingAssignments,
            'total_this_week' => Assignment::whereHas('booking', function ($query) use ($currentDate) {
                $query->whereBetween('booking_date', [$currentDate->copy()->startOfWeek(), $currentDate->copy()->endOfWeek()]);
            })->count()
        ];

        // Generate Time Slots from open_time to close_time with 30 min intervals
        $studioSetting = \App\Models\StudioSetting::first();
        $startHour = $studioSetting && $studioSetting->open_time ? Carbon::parse($studioSetting->open_time) : Carbon::createFromTime(8, 0);
        $endHour = $studioSetting && $studioSetting->close_time ? Carbon::parse($studioSetting->close_time) : Carbon::createFromTime(19, 0);
        $timeSlots = [];
        while ($startHour <= $endHour) {
            $timeSlots[] = $startHour->format('H:i');
            $startHour->addMinutes(30);
        }

        // Generate 2D array for the timeline grid
        $timeline = [];
        foreach ($timeSlots as $time) {
            $timeline[$time] = [];
            $slotTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $time);
            
            foreach ($employees as $employee) {
                // Check if employee has assignment at this exact slot (or covering this slot)
                $assignment = Assignment::with(['booking.package', 'booking.user'])
                    ->where('employee_id', $employee->id)
                    ->whereHas('booking', function($query) use ($currentDate, $slotTime) {
                        $query->whereDate('booking_date', $currentDate)
                              ->whereTime('start_time', '<=', $slotTime->format('H:i:s'))
                              ->whereTime('end_time', '>', $slotTime->format('H:i:s'));
                    })->first();

                if ($employee->leaveRequests->isNotEmpty()) {
                    $timeline[$time][$employee->id] = [
                        'status' => 'leave',
                        'assignment' => null
                    ];
                } elseif ($assignment) {
                    $timeline[$time][$employee->id] = [
                        'status' => 'booked',
                        'assignment' => $assignment
                    ];
                } else {
                    $timeline[$time][$employee->id] = [
                        'status' => 'free',
                        'assignment' => null
                    ];
                }
            }
        }

        $unassignedBookings = \App\Models\Booking::whereDoesntHave('assignments')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'package'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('admin.employee-schedules.index', compact('allEmployees', 'employees', 'stats', 'view', 'currentDate', 'timeSlots', 'timeline', 'unassignedBookings'));
    }
}
