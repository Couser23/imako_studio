<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('reports:generate')]
#[Description('Jalankan cron job untuk membuat dan mengirim laporan otomatis')]
class GenerateAutomatedReports extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $this->info("Menjalankan pembuatan laporan pada {$now->toDateTimeString()}");

        $admins = \App\Models\User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $prefs = $admin->notification_preferences;
            
            // Periksa apakah admin mengaktifkan laporan mingguan (walau ini namanya mingguan, freq-nya di report_freq)
            if (!($prefs['laporan_mingguan'] ?? false)) {
                continue;
            }

            $freq = $prefs['report_freq'] ?? 'harian';
            $shouldSend = false;
            $startDate = $now->copy();

            // Aturan pengiriman:
            // Harian: Setiap pukul 20:00
            if ($freq == 'harian' && $now->format('H:i') == '20:00') {
                $shouldSend = true;
                $startDate = $now->copy()->startOfDay();
            }
            // Mingguan: Setiap Senin pukul 08:00
            elseif ($freq == 'mingguan' && $now->dayOfWeek == \Carbon\Carbon::MONDAY && $now->format('H:i') == '08:00') {
                $shouldSend = true;
                $startDate = $now->copy()->subWeek()->startOfDay();
            }
            // Bulanan: Setiap tanggal 1 pukul 08:00
            elseif ($freq == 'bulanan' && $now->day == 1 && $now->format('H:i') == '08:00') {
                $shouldSend = true;
                $startDate = $now->copy()->subMonth()->startOfDay();
            }

            if ($shouldSend) {
                // Kumpulkan data statistik
                $totalRevenue = \App\Models\Payment::where('status', 'verified')
                    ->whereBetween('updated_at', [$startDate, $now])
                    ->sum('amount');
                
                $totalBookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $now])->count();
                
                $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();

                $reportData = [
                    'frequency' => $freq,
                    'total_revenue' => $totalRevenue,
                    'total_bookings' => $totalBookings,
                    'pending_payments' => $pendingPayments,
                ];

                \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\AdminReportMail($reportData));
                $this->info("Laporan {$freq} dikirim ke {$admin->email}");
            }
        }

        $this->info("Pembuatan laporan selesai.");
    }
}
