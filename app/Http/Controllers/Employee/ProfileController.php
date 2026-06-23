<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        
        $totalSessions = $user->assignments()->count();
        
        $sessionsThisMonth = $user->assignments()
            ->whereHas('booking', function ($q) {
                $start = now()->startOfMonth()->format('Y-m-d');
                $end = now()->endOfMonth()->format('Y-m-d');
                $q->whereBetween('booking_date', [$start, $end]);
            })->count();

        // Chart data
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $startOfMonth = now()->month($i)->startOfMonth()->format('Y-m-d');
            $endOfMonth = now()->month($i)->endOfMonth()->format('Y-m-d');

            $count = $user->assignments()
                ->whereHas('booking', function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);
                })->count();
            $chartData[] = $count;
        }
        
        $maxChart = max($chartData) ?: 1; // avoid division by zero
        $chartHeights = array_map(function($count) use ($maxChart) {
            return round(($count / $maxChart) * 100);
        }, $chartData);

        // Reviews
        $reviews = $user->reviewsReceived()->with(['booking.package', 'customer'])->orderBy('created_at', 'desc')->take(5)->get();
        $averageRating = $user->reviewsReceived()->avg('rating') ?? 0;

        return view('employee.profile.edit', compact('user', 'totalSessions', 'sessionsThisMonth', 'chartHeights', 'reviews', 'averageRating'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|required_with:current_password|string|min:8',
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('phone_number')) $user->phone_number = $request->phone_number;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('bio')) $user->bio = $request->bio;

        // Update password if provided
        if ($request->filled('current_password') && $request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('cover_image')) {
            $roleFolder = 'pegawai';
            $oldCover = $user->getOriginal('cover_image');
            if ($oldCover && file_exists(public_path('images/cover_profile/' . $roleFolder . '/' . $oldCover))) {
                unlink(public_path('images/cover_profile/' . $roleFolder . '/' . $oldCover));
            }
            $coverName = time() . '_cover.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('images/cover_profile/' . $roleFolder), $coverName);
            $user->cover_image = $coverName;
        }

        $user->save();

        return redirect()->route('employee.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
