<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addon;

class AddonController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'addons' => 'required|array|min:1',
            'addons.*.name' => 'required|string|max:255',
            'addons.*.price' => 'required|numeric|min:0',
            'addons.*.extra_minutes' => 'nullable|integer|min:0',
        ]);

        foreach ($request->addons as $addonData) {
            Addon::create([
                'category_id' => $request->category_id,
                'name' => $addonData['name'],
                'price' => $addonData['price'],
                'extra_minutes' => $addonData['extra_minutes'] ?? 0,
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', count($request->addons) . ' Layanan Tambahan berhasil ditambahkan sekaligus.');
    }

    public function update(Request $request, Addon $addon)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'extra_minutes' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $addon->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'extra_minutes' => $request->extra_minutes ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Layanan Tambahan berhasil diperbarui.');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();
        return redirect()->back()->with('success', 'Layanan Tambahan berhasil dihapus.');
    }
}
