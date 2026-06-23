<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CleanUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:clean-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus akun pengguna yang belum memverifikasi email lebih dari 5 menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = Carbon::now()->subMinutes(5);

        $deletedCount = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffTime)
            ->where('role', 'user') // Hanya pelanggan biasa, amannya jangan hapus admin/pegawai
            ->delete();

        $this->info("Berhasil menghapus {$deletedCount} akun pelanggan yang tidak terverifikasi (kedaluwarsa 5 menit).");
    }
}
