<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Helper untuk mengecek preferensi dan mengirim notif
     */
    protected function notifyAdmins(string $prefKey, string $type, string $title, string $message)
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $prefs = $admin->notification_preferences;
            if ($prefs[$prefKey] ?? false) {
                event(new \App\Events\AdminNotificationEvent($type, $title, $message));
            }
        }
    }

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $amount = number_format($payment->amount, 0, ',', '.');
        $userName = $payment->booking->user->name ?? 'Seseorang';
        
        $this->notifyAdmins(
            'pembayaran_diterima',
            'info',
            'Pembayaran Diterima',
            "{$userName} telah mengunggah bukti pembayaran sebesar Rp {$amount}."
        );
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
