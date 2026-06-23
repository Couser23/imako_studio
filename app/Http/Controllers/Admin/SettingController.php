<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $bufferTime = Setting::where('key', 'buffer_time_minutes')->value('value') ?? 30;
        
        return view('admin.settings.index', compact('bufferTime'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buffer_time_minutes' => ['required', 'integer', 'min:0', 'max:120'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'buffer_time_minutes'],
            ['value' => $validated['buffer_time_minutes']]
        );

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan jeda waktu berhasil diperbarui.');
    }
}
