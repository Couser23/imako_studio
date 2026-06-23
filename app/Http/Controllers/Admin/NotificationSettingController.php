<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    public function update(Request $request)
    {
        $prefs = [
            'booking_baru' => $request->boolean('booking_baru'),
            'pembayaran_diterima' => $request->boolean('pembayaran_diterima'),
            'booking_dibatalkan' => $request->boolean('booking_dibatalkan'),
            'pengingat_sesi' => $request->boolean('pengingat_sesi'),
            'laporan_mingguan' => $request->boolean('laporan_mingguan'),
            'pegawai_baru' => $request->boolean('pegawai_baru'),
            'notifikasi_realtime' => $request->boolean('notifikasi_realtime'),
            'validasi_menunggu' => $request->boolean('validasi_menunggu'),
            'hasil_belum_dikirim' => $request->boolean('hasil_belum_dikirim'),
            'sesi_akan_dimulai' => $request->boolean('sesi_akan_dimulai'),
            'report_freq' => $request->input('report_freq', 'harian'),
            'notification_sound' => $request->input('notification_sound', 'Ting (Default)'),
        ];

        $user = $request->user();
        $user->notification_preferences = $prefs;
        $user->save();

        ActivityLog::log('update_setting', 'Ubah preferensi notifikasi', 'Memperbarui pengaturan notifikasi');

        return back()->with('status', 'notifikasi-updated');
    }
}
