<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class TaskController extends Controller
{
    public function submitResult(Request $request, Booking $booking)
    {
        $request->validate([
            'result_link' => 'required|url',
            'result_notes' => 'nullable|string|max:1000'
        ]);

        // Pastikan pegawai yang mengirim hasil adalah yang di-assign ke booking ini
        $isAssigned = $booking->assignments()->where('employee_id', auth()->id())->exists();
        
        if (!$isAssigned) {
            return back()->with('error', 'Anda tidak berhak mengirimkan hasil untuk sesi ini.');
        }

        $booking->update([
            'result_link' => $request->result_link,
            'result_notes' => $request->result_notes,
            'status' => 'completed' // Otomatis tandai selesai jika diperlukan
        ]);

        \App\Models\ActivityLog::log(
            'submit_result',
            'Kirim Hasil Sesi',
            'Mengirimkan hasil foto untuk sesi #' . $booking->booking_code
        );

        return back()->with('success', 'Berhasil mengirimkan hasil sesi foto.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed'
        ]);

        $isAssigned = $booking->assignments()->where('employee_id', auth()->id())->exists();
        
        if (!$isAssigned) {
            return back()->with('error', 'Anda tidak berhak mengubah status sesi ini.');
        }

        $oldStatus = $booking->status;
        $booking->update([
            'status' => $request->status
        ]);

        $statusText = $request->status == 'in_progress' ? 'Sedang Berlangsung' : 'Selesai';
        \App\Models\ActivityLog::log(
            'update_status',
            'Update Status Sesi',
            'Mengubah status sesi #' . $booking->booking_code . ' menjadi ' . $statusText
        );

        return back()->with('success', 'Status sesi foto berhasil diubah.');
    }
}
