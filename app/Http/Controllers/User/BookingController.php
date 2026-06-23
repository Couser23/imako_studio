<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Package;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('package')->orderBy('created_at', 'desc')->get();
        return view('user.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        $bookings = \App\Models\Booking::whereIn('status', ['pending', 'waiting_payment', 'payment_uploaded', 'confirmed', 'in_progress', 'completed']) 
            ->with('package')
            ->get(['booking_date', 'start_time', 'end_time', 'package_id'])
            ->map(function ($booking) {
                return [
                    'booking_date' => \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d'),
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'category_id' => $booking->package->category_id ?? null,
                ];
            });
            
        $closedDates = \App\Models\ClosedDate::whereDate('end_date', '>=', now()->format('Y-m-01'))
            ->get(['start_date', 'end_date'])
            ->map(function ($cd) {
                return [
                    'start_date' => \Carbon\Carbon::parse($cd->start_date)->format('Y-m-d'),
                    'end_date' => \Carbon\Carbon::parse($cd->end_date)->format('Y-m-d'),
                ];
            });

        $settings = \Illuminate\Support\Facades\DB::table('studio_settings')->first();
        $addons = \Illuminate\Support\Facades\DB::table('addons')->where('is_active', true)->get();
        $paymentMethods = \Illuminate\Support\Facades\DB::table('payment_methods')->where('is_active', true)->get();

        return view('user.bookings.create', compact('packages', 'bookings', 'closedDates', 'settings', 'addons', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'nullable|image|max:5120',
        ]);

        $package = Package::findOrFail($request->package_id);
        
        $duration = $package->duration_minutes + ($package->gap_minutes ?? 0);
        $totalAddonsPrice = 0;
        
        $addonsData = [];
        if ($request->has('addons')) {
            $addons = \App\Models\Addon::whereIn('id', $request->addons)->get();
            foreach ($addons as $addon) {
                $duration += $addon->extra_minutes;
                $totalAddonsPrice += $addon->price;
                $addonsData[] = [
                    'addon_id' => $addon->id,
                    'quantity' => 1,
                    'price_at_booking' => $addon->price
                ];
            }
        }
        
        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($duration);

        // Check if the slot is available for this category
        $isAvailable = \App\Models\Booking::isTimeAvailable(
            $request->booking_date,
            $startTime->format('H:i:s'),
            $endTime->format('H:i:s'),
            $package->category_id
        );

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Mohon maaf, slot waktu tersebut sudah tidak tersedia untuk kategori ini. Silakan pilih waktu lain.')->withInput();
        }

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/bukti_bayar'), $filename);
            $paymentProofPath = $filename;
        }

        $paymentMethod = \Illuminate\Support\Facades\DB::table('payment_methods')->where('id', $request->payment_method_id)->first();
        
        $status = 'waiting_payment';
        if ($paymentProofPath) {
            $status = 'payment_uploaded';
        } elseif ($paymentMethod && strtolower($paymentMethod->name) === 'tunai') {
            $status = 'pending'; // or maybe just waiting_payment, or confirmed? we'll use pending.
        }

        $bookingCode = null;
        do {
            $bookingCode = strtoupper(\Illuminate\Support\Str::random(6));
        } while (\App\Models\Booking::where('booking_code', $bookingCode)->exists());

        $bookingId = \Illuminate\Support\Facades\DB::table('bookings')->insertGetId([
            'user_id' => auth()->id(),
            'booking_code' => $bookingCode,
            'package_id' => $package->id,
            'booking_date' => $request->booking_date,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'status' => $status,
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        foreach ($addonsData as $data) {
            \Illuminate\Support\Facades\DB::table('booking_addons')->insert([
                'booking_id' => $bookingId,
                'addon_id' => $data['addon_id'],
                'quantity' => $data['quantity'],
                'price_at_booking' => $data['price_at_booking'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $paymentId = \Illuminate\Support\Facades\DB::table('payments')->insertGetId([
            'booking_id' => $bookingId,
            'amount' => $package->price + $totalAddonsPrice,
            'payment_proof' => $paymentProofPath,
            'status' => 'pending',
            'payment_method_id' => $request->payment_method_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Trigger notifikasi real-time ke Dashboard Admin
        $payment = \App\Models\Payment::find($paymentId);
        if ($payment) {
            try {
                \Illuminate\Support\Facades\Log::info('Mencoba mengirim notifikasi Pesanan Baru: Payment ID ' . $payment->id);
                // Kita gunakan event() sama seperti test:notif
                event(new \App\Events\NewPaymentUploaded($payment));
                \Illuminate\Support\Facades\Log::info('Notifikasi Pesanan Baru berhasil dikirim!');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim notifikasi pesanan baru: ' . $e->getMessage());
            }
        }

        return redirect()->route('user.bookings.index')->with('success', 'Booking berhasil dibuat!');
    }
}
