<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
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
                // Opsional: jika mau kirim email bisa ditambahkan di sini
            }
        }
    }

    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        $this->notifyAdmins(
            'booking_baru',
            'success',
            'Reservasi Baru Masuk!',
            "Pelanggan {$booking->user->name} baru saja memesan paket {$booking->package->name}."
        );
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        if ($booking->isDirty('status') && $booking->status === 'cancelled') {
            $this->notifyAdmins(
                'booking_dibatalkan',
                'error',
                'Booking Dibatalkan',
                "Reservasi {$booking->package->name} oleh {$booking->user->name} telah dibatalkan."
            );
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
