<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pendapatan bulan ini
        $monthlyRevenue = Payment::where('status', 'verified')
            ->whereMonth('verified_at', Carbon::now()->month)
            ->whereYear('verified_at', Carbon::now()->year)
            ->sum('amount');
            
        // 2. Pemasukan hari ini
        $todayRevenue = Payment::where('status', 'verified')
            ->whereDate('verified_at', Carbon::today())
            ->sum('amount');
            
        // Pemasukan kemarin untuk persentase
        $yesterdayRevenue = Payment::where('status', 'verified')
            ->whereDate('verified_at', Carbon::yesterday())
            ->sum('amount');
            
        $revenueGrowth = 0;
        if ($yesterdayRevenue > 0) {
            $revenueGrowth = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
        } elseif ($todayRevenue > 0) {
            $revenueGrowth = 100;
        }
            
        // 3. Booking masuk
        $newBookings = Booking::whereIn('status', ['pending', 'waiting_payment'])->count();
        $waitingVerification = Payment::where('status', 'pending')->count();
        
        // 4. Total Pegawai & 5. Total Pengguna
        $totalEmployees = User::where('role', 'pegawai')->count();
        $totalUsers = User::where('role', 'user')->count();
        
        // 6. Jadwal sesi hari ini
        $todaySchedules = Booking::with(['package', 'user', 'assignments.employee'])
            ->whereDate('booking_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->take(5)
            ->get();
            
        // 7. Pesanan terbaru
        $recentBookings = Booking::with(['package', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 8. 7 Hari Terakhir Revenue
        $last7DaysRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = Payment::where('status', 'verified')
                ->whereDate('verified_at', $date)
                ->sum('amount');
            $last7DaysRevenue[] = [
                'day' => $date->format('D'),
                'amount' => $amount
            ];
        }

        // 9. Booking per paket (Bulan ini)
        $packageBookings = \App\Models\Package::withCount(['bookings' => function ($query) {
            $query->whereMonth('booking_date', Carbon::now()->month)
                  ->whereYear('booking_date', Carbon::now()->year);
        }])->orderByDesc('bookings_count')->get();

        // Calculate max for percentage width
        $maxPackageBookings = $packageBookings->max('bookings_count') ?: 1;

        // 10. Status pegawai hari ini
        $now = Carbon::now();
        $employees = User::where('role', 'pegawai')->get();
        $employeeStatuses = $employees->map(function ($employee) use ($now) {
            $currentAssignment = \App\Models\Assignment::where('employee_id', $employee->id)
                ->whereHas('booking', function ($query) use ($now) {
                    $query->whereDate('booking_date', $now->toDateString())
                          ->whereTime('start_time', '<=', $now->toTimeString())
                          ->whereTime('end_time', '>=', $now->toTimeString());
                })->first();

            $todayAssignmentsCount = \App\Models\Assignment::where('employee_id', $employee->id)
                ->whereHas('booking', function ($query) use ($now) {
                    $query->whereDate('booking_date', $now->toDateString());
                })->count();

            $status = 'Free';
            $statusClass = 'bg-gray-100 text-gray-600';
            $timeSlot = '-';

            if ($currentAssignment) {
                $status = 'Bertugas';
                $statusClass = 'bg-[#84cc16] text-white';
                $timeSlot = \Carbon\Carbon::parse($currentAssignment->booking->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($currentAssignment->booking->end_time)->format('H:i');
            }

            return (object) [
                'name' => $employee->name,
                'status' => $status,
                'statusClass' => $statusClass,
                'timeSlot' => $timeSlot,
                'sessionCount' => $todayAssignmentsCount
            ];
        });

        return view('admin.dashboard', compact(
            'monthlyRevenue',
            'todayRevenue',
            'revenueGrowth',
            'newBookings',
            'waitingVerification',
            'totalEmployees',
            'totalUsers',
            'todaySchedules',
            'recentBookings',
            'last7DaysRevenue',
            'packageBookings',
            'maxPackageBookings',
            'employeeStatuses'
        ));
    }
}
