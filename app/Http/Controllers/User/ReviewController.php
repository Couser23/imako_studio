<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        // Pastikan hanya bisa review booking yang sudah selesai dan miliknya
        if ($booking->user_id !== auth()->id() || $booking->status !== 'completed') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Cek apakah sudah direview untuk employee tersebut (atau studio jika null)
        $existingReview = Review::where('booking_id', $booking->id)
            ->where('employee_id', $request->employee_id)
            ->where('customer_id', auth()->id())
            ->first();

        if ($existingReview) {
            return response()->json(['message' => 'Anda sudah memberikan ulasan untuk ini.'], 422);
        }

        $review = Review::create([
            'customer_id' => auth()->id(),
            'employee_id' => $request->employee_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Ulasan berhasil dikirim.',
            'review' => $review
        ]);
    }
}
