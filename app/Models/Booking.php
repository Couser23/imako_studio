<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'booking_code', 'package_id', 'booking_date', 'start_time', 'end_time', 'status', 'result_link', 'notes', 'result_notes'])]
class Booking extends Model
{
    protected $casts = [
        'booking_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')->withPivot('quantity', 'price_at_booking');
    }

    public function getTotalPriceAttribute()
    {
        $total = 0;
        if ($this->package) {
            $total += $this->package->discount_price ?? $this->package->price;
        }
        
        foreach ($this->addons as $addon) {
            $total += ($addon->pivot->price_at_booking * $addon->pivot->quantity);
        }

        return $total;
    }

    /**
     * Check if a specific time slot is available (no overlapping bookings)
     */
    public static function isTimeAvailable($date, $startTime, $endTime, $categoryId = null, $ignoreBookingId = null)
    {
        // 1. Check if the date falls within any ClosedDate (Libur Studio)
        $isClosed = \App\Models\ClosedDate::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();

        if ($isClosed) {
            return false;
        }

        // 2. Check overlapping bookings
        $query = self::whereDate('booking_date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Overlaps if an existing booking starts before the new one ends 
                    // AND ends after the new one starts
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->whereIn('status', ['pending', 'waiting_payment', 'payment_uploaded', 'confirmed', 'in_progress', 'completed']);

        if ($categoryId) {
            $query->whereHas('package', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($ignoreBookingId) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return $query->doesntExist();
    }
}
