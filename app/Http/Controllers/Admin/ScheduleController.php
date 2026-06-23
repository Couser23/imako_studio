<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Auto-complete bookings that have passed their end time
        Booking::whereIn('status', ['confirmed', 'in_progress'])
            ->where(function ($query) {
                $query->whereDate('booking_date', '<', Carbon::today())
                      ->orWhere(function ($q) {
                          $q->whereDate('booking_date', Carbon::today())
                            ->whereTime('end_time', '<', Carbon::now()->format('H:i:s'));
                      });
            })
            ->update(['status' => 'completed']);

        $view = $request->query('view', 'kalender');
        $currentMonth = $request->query('month', Carbon::today()->month);
        $currentYear = $request->query('year', Carbon::today()->year);
        $currentDate = Carbon::createFromDate($currentYear, $currentMonth, 1);
        
        $query = Booking::with(['user', 'package', 'assignments.employee'])
            ->whereMonth('booking_date', $currentMonth)
            ->whereYear('booking_date', $currentYear)
            ->where('status', '!=', 'cancelled');
            
        $todayQuery = Booking::with(['user', 'package', 'assignments.employee'])
            ->whereDate('booking_date', Carbon::today())
            ->where('status', '!=', 'cancelled');

        // Helper function to apply common filters
        $applyFilters = function($q) use ($request) {
            if ($search = $request->query('search')) {
                // Remove #IMK- prefix if user typed it
                $cleanSearch = str_ireplace(['#IMK-', '#IMK', '#imk-', '#imk'], '', $search);
                $q->where(function ($sq) use ($search, $cleanSearch) {
                    $sq->where('booking_code', 'like', "%{$cleanSearch}%")
                      ->orWhereHas('user', function ($uq) use ($search) {
                          $uq->where('name', 'like', "%{$search}%");
                      });
                });
            }
            if ($packageId = $request->query('package_id')) {
                $q->where('package_id', $packageId);
            }
            if ($employeeId = $request->query('employee_id')) {
                $q->whereHas('assignments', function ($sq) use ($employeeId) {
                    $sq->where('employee_id', $employeeId);
                });
            }
            if ($status = $request->query('status')) {
                $q->where('status', $status);
            }
            if ($date = $request->query('date')) {
                $q->whereDate('booking_date', $date);
            }
        };

        $applyFilters($query);
        $applyFilters($todayQuery);

        $bookings = $query->orderBy('booking_date')->orderBy('start_time')->get();
        $listQuery = clone $query;
        $listBookings = $listQuery->orderBy('booking_date')->orderBy('start_time')->paginate(25)->appends($request->query());
        $todayBookings = $todayQuery->orderBy('start_time')->get();
        
        $stats = [
            'today' => $todayBookings->count(),
            'ongoing' => Booking::whereDate('booking_date', Carbon::today())
                                ->whereTime('start_time', '<=', Carbon::now())
                                ->whereTime('end_time', '>=', Carbon::now())
                                ->count(),
            'this_month' => $bookings->count(),
            'unassigned' => Booking::whereMonth('booking_date', $currentMonth)->doesntHave('assignments')->count(),
            'slots' => 5 // Example static value
        ];

        $employees = \App\Models\User::where('role', 'pegawai')->with(['leaveRequests' => function ($q) {
            $q->where('status', 'approved');
        }])->get();
        $packages = \App\Models\Package::select('id', 'name')->get();

        // Generate calendar days
        $daysInMonth = $currentDate->daysInMonth;
        $startDayOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday, 6 = Saturday
        $calendar = [];
        
        // previous month days to fill first row
        $prevMonth = $currentDate->copy()->subMonth();
        $daysInPrevMonth = $prevMonth->daysInMonth;
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = [
                'day' => $daysInPrevMonth - ($startDayOfWeek - 1 - $i),
                'is_current_month' => false,
                'date' => $prevMonth->copy()->day($daysInPrevMonth - ($startDayOfWeek - 1 - $i)),
                'events' => collect()
            ];
        }
        
        // current month days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $currentDate->copy()->day($day);
            $dayEvents = $bookings->filter(function($b) use ($date) {
                return $b->booking_date->isSameDay($date);
            });
            $calendar[] = [
                'day' => $day,
                'is_current_month' => true,
                'date' => $date,
                'events' => $dayEvents
            ];
        }
        
        // next month days to fill last row
        $totalCells = ceil(count($calendar) / 7) * 7;
        $remainingCells = $totalCells - count($calendar);
        $nextMonth = $currentDate->copy()->addMonth();
        for ($i = 1; $i <= $remainingCells; $i++) {
            $calendar[] = [
                'day' => $i,
                'is_current_month' => false,
                'date' => $nextMonth->copy()->day($i),
                'events' => collect()
            ];
        }

        return view('admin.schedules.index', compact('bookings', 'todayBookings', 'stats', 'view', 'employees', 'calendar', 'currentDate', 'packages', 'listBookings'));
    }

    public function assign(Request $request, Booking $booking)
    {
        $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:users,id',
            'result_link' => 'nullable|url',
            'result_notes' => 'nullable|string|max:1000'
        ]);

        // Check if ANY of the selected employees are on leave
        foreach ($request->employee_ids as $empId) {
            $isOnLeave = \App\Models\LeaveRequest::where('user_id', $empId)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $booking->booking_date)
                ->whereDate('end_date', '>=', $booking->booking_date)
                ->exists();

            if ($isOnLeave) {
                $employee = \App\Models\User::find($empId);
                return redirect()->back()->with('error', "Gagal menugaskan: Pegawai {$employee->name} sedang libur (cuti) pada tanggal tersebut.");
            }
        }

        // Remove old assignments
        \App\Models\Assignment::where('booking_id', $booking->id)
            ->whereNotIn('employee_id', $request->employee_ids)
            ->delete();

        // Create or update assignments
        $assignedNames = [];
        foreach ($request->employee_ids as $empId) {
            $assignment = \App\Models\Assignment::updateOrCreate(
                ['booking_id' => $booking->id, 'employee_id' => $empId],
                ['assigned_by' => auth()->id()]
            );
            $assignedNames[] = \App\Models\User::find($empId)->name;
            
            // Dispatch notification to the employee
            $date = \Carbon\Carbon::parse($booking->booking_date)->format('d M');
            $time = \Carbon\Carbon::parse($booking->start_time)->format('H:i');
            $message = "Anda ditugaskan pada sesi foto {$booking->package?->name} tanggal {$date} jam {$time}.";
            event(new \App\Events\UserNotificationEvent($empId, 'info', 'Penugasan Jadwal Baru', $message));
        }

        $updateData = [];
        if ($request->has('result_link')) {
            $updateData['result_link'] = $request->result_link;
        }
        if ($request->has('result_notes')) {
            $updateData['result_notes'] = $request->result_notes;
        }
        if (!empty($updateData)) {
            $booking->update($updateData);
        }

        $namesStr = implode(', ', $assignedNames);
        \App\Models\ActivityLog::log('assign_employee', "Assign {$namesStr} ke BK-{$booking->id}", $booking->package?->name . ' · ' . $booking->booking_date->format('d M H:i'));

        return redirect()->back()->with('success', 'Penugasan dan Link Hasil berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled'
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    public function block(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'package_id' => 'required|exists:packages,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string'
        ]);

        // Validate if slot is available
        $isAvailable = \App\Models\Booking::isTimeAvailable(
            $request->booking_date,
            $request->start_time,
            $request->end_time
        );

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Gagal memblokir: Terdapat jadwal yang bertabrakan pada rentang waktu tersebut.');
        }

        $bookingCode = null;
        do {
            $bookingCode = strtoupper(\Illuminate\Support\Str::random(6));
        } while (\App\Models\Booking::where('booking_code', $bookingCode)->exists());

        \App\Models\Booking::create([
            'user_id' => auth()->id(), // Using admin's ID
            'booking_code' => $bookingCode,
            'package_id' => $request->package_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'confirmed', 
            'notes' => $request->notes ?: 'Blokir Manual'
        ]);

        return redirect()->back()->with('success', 'Slot berhasil diblokir.');
    }
}
