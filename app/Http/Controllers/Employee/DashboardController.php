<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');
        
        // Sesi hari ini
        $assignmentsToday = $user->assignments()
            ->whereHas('booking', function ($query) use ($today) {
                $query->where('booking_date', $today);
            })
            ->with(['booking.package', 'booking.user'])
            ->get()
            ->sortBy(function ($assignment) {
                return $assignment->booking->start_time;
            });
            
        // Hasil belum dikirim
        $pendingResults = $user->assignments()
            ->whereHas('booking', function ($query) {
                $query->whereNull('result_link');
                // You might also want to check status, e.g., only completed or in progress sessions.
            })
            ->with(['booking.package', 'booking.user'])
            ->get();
            
        // Sesi bulan ini
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        $assignmentsMonth = $user->assignments()
            ->whereHas('booking', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);
            })->count();
            
        // Total sesi all time
        $totalAssignments = $user->assignments()->count();
        $firstAssignment = $user->assignments()->orderBy('created_at', 'asc')->first();
        $joinedDate = $firstAssignment ? Carbon::parse($firstAssignment->created_at)->translatedFormat('M Y') : Carbon::parse($user->created_at)->translatedFormat('M Y');
        
        // Sesi bulan lalu
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $assignmentsLastMonth = $user->assignments()
            ->whereHas('booking', function ($query) use ($startOfLastMonth, $endOfLastMonth) {
                $query->whereBetween('booking_date', [$startOfLastMonth, $endOfLastMonth]);
            })->count();
            
        $monthDiff = $assignmentsMonth - $assignmentsLastMonth;
        
        // Waktu sesi hari ini
        $todayTimeRange = 'Belum ada jadwal';
        if ($assignmentsToday->count() > 0) {
            $firstSession = $assignmentsToday->first();
            $lastSession = $assignmentsToday->last();
            $todayTimeRange = Carbon::parse($firstSession->booking->start_time)->format('H.i') . ' - ' . Carbon::parse($lastSession->booking->end_time)->format('H.i');
        }
        
        // Aktivitas terbaru pegawai
        $activityLogs = \App\Models\ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Try to get jeda duration setting, default 30
        $jedaSetting = \App\Models\Setting::where('key', 'jeda_duration')->first();
        $jedaDuration = $jedaSetting ? (int)$jedaSetting->value : 30;

        $studioSetting = \App\Models\StudioSetting::first();

        return view('employee.dashboard', compact(
            'assignmentsToday',
            'pendingResults',
            'assignmentsMonth',
            'totalAssignments',
            'joinedDate',
            'monthDiff',
            'todayTimeRange',
            'jedaDuration',
            'activityLogs',
            'studioSetting',
            'today'
        ));
    }

    public function jadwal(Request $request)
    {
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);
        $user = auth()->user();
        
        $assignments = $user->assignments()
            ->whereHas('booking', function ($query) use ($date) {
                $query->where('booking_date', $date);
            })
            ->with(['booking.package', 'booking.user'])
            ->get()
            ->sortBy(function ($assignment) {
                return $assignment->booking->start_time;
            });

        $jedaSetting = \App\Models\Setting::where('key', 'jeda_duration')->first();
        $jedaDuration = $jedaSetting ? (int)$jedaSetting->value : 30;

        $studioSetting = \App\Models\StudioSetting::first();
        
        $allAssignments = $user->assignments()
            ->whereHas('booking')
            ->with('booking')
            ->get()
            ->map(function ($assignment) {
                return [
                    'booking_date' => \Carbon\Carbon::parse($assignment->booking->booking_date)->format('Y-m-d'),
                ];
            });

        return view('employee.jadwal', compact('assignments', 'carbonDate', 'jedaDuration', 'studioSetting', 'allAssignments'));
    }

    public function tugas(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->assignments()->with(['booking.package', 'booking.user']);

        if ($search = $request->query('search')) {
            $cleanSearch = str_ireplace(['#IMK-', '#IMK', '#imk-', '#imk'], '', $search);
            $query->whereHas('booking', function ($q) use ($search, $cleanSearch) {
                $q->where('booking_code', 'like', "%{$cleanSearch}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($filterDate = $request->query('date_filter')) {
            if ($filterDate === 'today') {
                $query->whereHas('booking', function ($q) {
                    $q->whereDate('booking_date', \Carbon\Carbon::today());
                });
            } elseif ($filterDate === 'this_week') {
                $query->whereHas('booking', function ($q) {
                    $q->whereBetween('booking_date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
                });
            } elseif ($filterDate === 'this_month') {
                $query->whereHas('booking', function ($q) {
                    $q->whereMonth('booking_date', \Carbon\Carbon::now()->month)
                      ->whereYear('booking_date', \Carbon\Carbon::now()->year);
                });
            }
        }

        $assignments = $query->get()
            ->sortByDesc(function ($assignment) {
                // sort by date descending
                return $assignment->booking->booking_date . ' ' . $assignment->booking->start_time;
            });

        $jedaSetting = \App\Models\Setting::where('key', 'jeda_duration')->first();
        $jedaDuration = $jedaSetting ? (int)$jedaSetting->value : 30;

        $pendingTasks = [];
        $completedTasks = [];

        foreach ($assignments as $assignment) {
            $booking = $assignment->booking;
            
            // Handle booking_date correctly whether it's a string or Carbon instance
            $bookingDateStr = is_string($booking->booking_date) ? $booking->booking_date : $booking->booking_date->format('Y-m-d');
            $sessionStart = \Carbon\Carbon::parse($bookingDateStr . ' ' . $booking->start_time);
            $sessionEnd = \Carbon\Carbon::parse($bookingDateStr . ' ' . $booking->end_time);
            
            $isNow = $booking->status === 'in_progress';
            $isResultSent = !empty($booking->result_link) || $booking->status === 'completed';
            
            $task = [
                'bookingId' => $booking->id,
                'bookingNo' => '#IMK-' . $booking->booking_code,
                'clientName' => $booking->user->name ?? 'Klien',
                'packageName' => $booking->package->name ?? 'Paket',
                'dateFmt' => \Carbon\Carbon::parse($bookingDateStr)->translatedFormat('d M Y'),
                'startFmt' => \Carbon\Carbon::parse($booking->start_time)->format('H.i'),
                'endFmt' => \Carbon\Carbon::parse($booking->end_time)->format('H.i'),
                'duration' => \Carbon\Carbon::parse($booking->start_time)->diffInMinutes(\Carbon\Carbon::parse($booking->end_time)),
                'jeda' => $jedaDuration,
                'notes' => $booking->notes ?? 'Tidak ada catatan.',
                'status' => $booking->status,
                'resultLink' => $booking->result_link ?? '',
                'resultNotes' => $booking->result_notes ?? '',
                'isNow' => $isNow,
                'isSent' => $isResultSent
            ];

            if ($isResultSent) {
                $completedTasks[] = $task;
            } else {
                $pendingTasks[] = $task;
            }
        }

        return view('employee.tugas', compact('pendingTasks', 'completedTasks'));
    }

    public function kirimHasil()
    {
        $user = auth()->user();
        
        $assignments = $user->assignments()
            ->with(['booking.package', 'booking.user'])
            ->get()
            ->sortByDesc(function ($assignment) {
                return $assignment->booking->booking_date . ' ' . $assignment->booking->start_time;
            });

        $pendingResults = [];
        $sentResults = [];

        foreach ($assignments as $assignment) {
            $booking = $assignment->booking;
            
            $bookingDateStr = is_string($booking->booking_date) ? $booking->booking_date : $booking->booking_date->format('Y-m-d');
            $sessionEnd = \Carbon\Carbon::parse($bookingDateStr . ' ' . $booking->end_time);
            
            // Only consider tasks that are done/past as eligible for sending results
            $isPast = $sessionEnd <= now() || $booking->status === 'completed';
            
            $task = [
                'bookingId' => $booking->id,
                'bookingNo' => '#IMK-' . $booking->booking_code,
                'clientName' => $booking->user->name ?? 'Klien',
                'packageName' => $booking->package->name ?? 'Paket',
                'dateFmt' => \Carbon\Carbon::parse($bookingDateStr)->translatedFormat('d M Y'),
                'sentAt' => $booking->updated_at ? $booking->updated_at->translatedFormat('d M, H.i') : '-',
                'resultLink' => $booking->result_link ?? '',
                'resultNotes' => $booking->result_notes ?? ''
            ];

            if (!empty($booking->result_link)) {
                $sentResults[] = $task;
            } elseif ($isPast) {
                $pendingResults[] = $task;
            }
        }

        return view('employee.kirim-hasil', compact('pendingResults', 'sentResults'));
    }

    public function pengajuanLibur()
    {
        $user = auth()->user();
        $history = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($request) {
                $startDate = \Carbon\Carbon::parse($request->start_date)->translatedFormat('d M Y');
                $endDate = \Carbon\Carbon::parse($request->end_date)->translatedFormat('d M Y');
                $dateDisplay = $startDate === $endDate ? $startDate : $startDate . ' - ' . $endDate;
                
                return [
                    'date' => $dateDisplay,
                    'reason' => $request->reason,
                    'status' => $request->status === 'approved' ? 'Disetujui' : ($request->status === 'rejected' ? 'Ditolak' : 'Menunggu')
                ];
            });

        return view('employee.pengajuan-libur', compact('history'));
    }

    public function storePengajuanLibur(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        \App\Models\LeaveRequest::create([
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason ?? 'Tidak ada keterangan',
            'status' => 'pending',
        ]);

        return redirect()->route('employee.pengajuan_libur')->with('success', 'Pengajuan libur berhasil dikirim.');
    }
}
