<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with('user');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->get();
        return view('admin.leave-requests.index', compact('leaveRequests'));
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string'
        ]);

        $leaveRequest->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);
        
        $statusIndo = $request->status === 'approved' ? 'Disetujui' : 'Ditolak';
        $type = $request->status === 'approved' ? 'success' : 'error';
        $message = "Pengajuan libur Anda untuk tanggal " . \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') . " telah {$statusIndo}.";
        if ($request->admin_note) {
            $message .= " Catatan Admin: " . $request->admin_note;
        }

        event(new \App\Events\UserNotificationEvent($leaveRequest->user_id, $type, 'Pengajuan Libur ' . $statusIndo, $message));

        return redirect()->back()->with('success', 'Status pengajuan libur berhasil diperbarui.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->back()->with('success', 'Pengajuan libur berhasil dihapus.');
    }
}
