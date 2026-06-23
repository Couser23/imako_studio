<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PaymentValidationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'menunggu');
        $query = \App\Models\Payment::with(['booking.user', 'booking.package'])->latest();

        if ($tab === 'menunggu') {
            $query->where('status', 'pending');
        } elseif ($tab === 'konfirmasi') {
            $query->where('status', 'verified');
        } elseif ($tab === 'ditolak') {
            $query->where('status', 'rejected');
        }

        // Apply filters
        if ($search = $request->query('search')) {
            $cleanSearch = str_ireplace(['#IMK-', '#IMK', '#imk-', '#imk'], '', $search);

            $query->where(function ($q) use ($search, $cleanSearch) {
                $q->whereHas('booking', function ($bq) use ($cleanSearch) {
                    $bq->where('booking_code', 'like', "%{$cleanSearch}%");
                })
                ->orWhereHas('booking.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($packageId = $request->query('package_id')) {
            $query->whereHas('booking', function ($q) use ($packageId) {
                $q->where('package_id', $packageId);
            });
        }

        if ($date = $request->query('date')) {
            $query->whereHas('booking', function ($q) use ($date) {
                $q->whereDate('booking_date', $date);
            });
        }

        $payments = $query->paginate(10)->appends($request->query());
        
        $packages = \App\Models\Package::select('id', 'name')->get();
        $stats = [
            'pending' => \App\Models\Payment::where('status', 'pending')->count(),
            'verified_today' => \App\Models\Payment::where('status', 'verified')->whereDate('updated_at', today())->count(),
            'rejected_today' => \App\Models\Payment::where('status', 'rejected')->whereDate('updated_at', today())->count(),
            'verified_all' => \App\Models\Payment::where('status', 'verified')->count(),
            'rejected_all' => \App\Models\Payment::where('status', 'rejected')->count(),
            'total_all' => \App\Models\Payment::count(),
            'total_this_month' => \App\Models\Payment::whereMonth('created_at', now()->month)->count(),
            'payment_methods_count' => \App\Models\PaymentMethod::count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats', 'tab', 'packages'));
    }

    public function updateStatus(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $payment->update([
            'status' => $request->status,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Auto update booking status based on payment verification
        if ($payment->booking) {
            if ($request->status === 'verified') {
                $payment->booking->update(['status' => 'confirmed']);
            } elseif ($request->status === 'rejected') {
                $payment->booking->update(['status' => 'cancelled']);
            }
        }

        $userName = $payment->booking?->user?->name ?? 'Unknown';
        $amount = number_format($payment->amount, 0, ',', '.');
        $bookingCode = $payment->booking ? $payment->booking->booking_code : str_pad($payment->booking_id, 3, '0', STR_PAD_LEFT);
        
        if ($request->status === 'verified') {
            ActivityLog::log('confirm_payment', "Konfirmasi pembayaran {$userName}", "Booking #IMK-{$bookingCode} · Rp {$amount}");
            if ($payment->booking && $payment->booking->user_id) {
                event(new \App\Events\UserNotificationEvent($payment->booking->user_id, 'success', 'Pembayaran Dikonfirmasi', 'Pembayaran Anda untuk Booking #IMK-' . $bookingCode . ' telah berhasil dikonfirmasi.'));
            }
        } else {
            ActivityLog::log('reject_payment', "Tolak pembayaran {$userName}", "Booking #IMK-{$bookingCode} · Rp {$amount}");
            if ($payment->booking && $payment->booking->user_id) {
                event(new \App\Events\UserNotificationEvent($payment->booking->user_id, 'error', 'Pembayaran Ditolak', 'Pembayaran Anda untuk Booking #IMK-' . $bookingCode . ' tidak valid. Silakan hubungi admin.'));
            }
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
