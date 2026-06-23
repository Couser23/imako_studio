<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioSetting;
use Illuminate\Http\Request;

class StudioSettingController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'open_time' => 'required',
            'close_time' => 'required',
            'operational_days' => 'nullable|array',
            'min_booking_hours' => 'required|integer',
            'max_booking_months' => 'required|integer',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'whatsapp_link' => 'nullable|string|max:255',
        ]);

        $setting = StudioSetting::first();
        if (!$setting) {
            $setting = new StudioSetting();
        }

        $setting->fill($validated);
        $setting->save();

        \App\Models\ActivityLog::log('update_setting', 'Ubah pengaturan studio', 'Memperbarui informasi studio: ' . $validated['name']);

        return back()->with('status', 'studio-info-updated');
    }

    public function storeCloseDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'reason' => 'nullable|string|max:255',
        ]);

        $start = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        \App\Models\ClosedDate::create([
            'start_date' => $start,
            'end_date' => $end,
            'reason' => $request->reason,
        ]);

        \App\Models\ActivityLog::log('update_setting', 'Tutup Studio', "Menambahkan jadwal libur dari {$request->start_date} s/d {$request->end_date}");

        return back()->with('status', 'studio-info-updated');
    }

    public function destroyCloseDate($id)
    {
        $closedDate = \App\Models\ClosedDate::findOrFail($id);
        $closedDate->delete();

        \App\Models\ActivityLog::log('update_setting', 'Buka Studio', "Menghapus jadwal libur.");

        return back()->with('status', 'studio-info-updated');
    }
}
