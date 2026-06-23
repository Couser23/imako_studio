<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('notifications:run-scheduled')]
#[Description('Jalankan cron job untuk memeriksa peringatan notifikasi admin')]
class RunScheduledNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $this->info("Menjalankan pengecekan notifikasi pada {$now->toDateTimeString()}");

        // Ambil semua admin
        $admins = \App\Models\User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $prefs = $admin->notification_preferences;

            // 1. Sesi akan dimulai (30 mnt)
            if ($prefs['sesi_akan_dimulai'] ?? false) {
                $targetTime = $now->copy()->addMinutes(30)->format('H:i');
                $bookings = \App\Models\Booking::whereDate('booking_date', $now->toDateString())
                    ->whereRaw("DATE_FORMAT(start_time, '%H:%i') = ?", [$targetTime])
                    ->where('status', 'confirmed')
                    ->get();

                foreach ($bookings as $booking) {
                    event(new \App\Events\AdminNotificationEvent(
                        'warning', 
                        'Sesi Segera Dimulai', 
                        "Sesi {$booking->package->name} dengan {$booking->user->name} akan dimulai 30 menit lagi ({$targetTime})."
                    ));
                    $this->info("Notifikasi sesi 30 mnt dikirim untuk Booking #{$booking->id}");
                }
            }

            // 2. Validasi pembayaran menunggu (Misal: sudah 30 menit pending)
            if ($prefs['validasi_menunggu'] ?? false) {
                $payments = \App\Models\Payment::where('status', 'pending')
                    ->whereBetween('created_at', [$now->copy()->subMinutes(31), $now->copy()->subMinutes(30)])
                    ->get();

                foreach ($payments as $payment) {
                    event(new \App\Events\AdminNotificationEvent(
                        'warning', 
                        'Pembayaran Menunggu Validasi', 
                        "Ada pembayaran sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " yang belum divalidasi lebih dari 30 menit."
                    ));
                    $this->info("Notifikasi validasi menunggu dikirim untuk Payment #{$payment->id}");
                }
            }

            // 3. Hasil foto belum dikirim (Misal: booking selesai hari kemarin, tapi status masih in_progress atau hasil belum ada)
            // (Karena struktur db hasil foto belum begitu jelas, kita asumsikan status in_progress sejak kemarin)
            if ($prefs['hasil_belum_dikirim'] ?? false) {
                $uncompletedBookings = \App\Models\Booking::where('status', 'in_progress')
                    ->whereDate('booking_date', $now->copy()->subDay()->toDateString())
                    ->whereRaw("DATE_FORMAT(end_time, '%H:%i') = ?", [$now->format('H:i')]) // Tepat 24 jam setelah sesi selesai
                    ->get();

                foreach ($uncompletedBookings as $booking) {
                    event(new \App\Events\AdminNotificationEvent(
                        'error', 
                        'Hasil Foto Belum Dikirim', 
                        "Sesi {$booking->package->name} (Pelanggan: {$booking->user->name}) sudah lewat 24 jam tapi belum diselesaikan/dikirim."
                    ));
                    $this->info("Notifikasi hasil belum dikirim untuk Booking #{$booking->id}");
                }
            }
        }

        $this->info("Pengecekan selesai.");
    }
}
