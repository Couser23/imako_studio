<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $validated['account_number'] = $validated['account_number'] ?? '-';
        $validated['account_name'] = $validated['account_name'] ?? '-';

        if ($request->hasFile('logo')) {
            $logoName = time() . '.' . $request->logo->extension();  
            $request->logo->move(public_path('images/metode_pembayaran'), $logoName);
            $validated['logo'] = $logoName;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode Pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $validated['account_number'] = $validated['account_number'] ?? '-';
        $validated['account_name'] = $validated['account_name'] ?? '-';

        if ($request->hasFile('logo')) {
            if ($paymentMethod->logo && file_exists(public_path('images/metode_pembayaran/' . $paymentMethod->logo))) {
                unlink(public_path('images/metode_pembayaran/' . $paymentMethod->logo));
            }
            
            $logoName = time() . '.' . $request->logo->extension();  
            $request->logo->move(public_path('images/metode_pembayaran'), $logoName);
            $validated['logo'] = $logoName;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode Pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->logo && file_exists(public_path('images/metode_pembayaran/' . $paymentMethod->logo))) {
            unlink(public_path('images/metode_pembayaran/' . $paymentMethod->logo));
        }
        
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode Pembayaran berhasil dihapus.');
    }
}
