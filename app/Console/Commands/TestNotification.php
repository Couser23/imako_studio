<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('test:notif')]
#[Description('Kirim notifikasi tes ke dashboard admin')]
class TestNotification extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        event(new \App\Events\AdminNotificationEvent('info', 'Notifikasi Berhasil!', 'Sistem real-time berfungsi dengan sangat baik.'));
        
        // Coba tembak event Payment juga untuk ngetest
        event(new \App\Events\NewPaymentUploaded());
        
        // Tembak juga ke semua Pegawai untuk ngetest
        $pegawais = \App\Models\User::where('role', 'pegawai')->get();
        foreach ($pegawais as $pegawai) {
            event(new \App\Events\UserNotificationEvent($pegawai->id, 'info', 'Pesan Uji Coba', 'Halo Pegawai! Sinyal Echo Anda berfungsi sempurna.'));
        }
        
        // Tembak juga ke semua User (Pelanggan) untuk ngetest
        $pelanggans = \App\Models\User::whereNotIn('role', ['admin', 'pegawai'])->get();
        foreach ($pelanggans as $pelanggan) {
            event(new \App\Events\UserNotificationEvent($pelanggan->id, 'info', 'Pesan Uji Coba', 'Halo Pelanggan! Sinyal Echo Anda berfungsi sempurna.'));
        }
        
        $this->info('Notifikasi berhasil dikirim!');
    }
}
