<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'price', 'discount_price', 'duration_minutes', 'gap_minutes', 'terms_and_conditions', 'tag', 'is_active', 'image', 'category_id'])]
class Package extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
