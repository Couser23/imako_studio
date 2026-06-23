<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
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
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role === 'pegawai') {
            $this->notifyAdmins(
                'pegawai_baru',
                'info',
                'Pegawai Baru Terdaftar',
                "Akun pegawai baru bernama {$user->name} telah ditambahkan ke sistem."
            );
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
