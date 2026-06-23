<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['category_id', 'name', 'price', 'extra_minutes', 'is_active'])]
class Addon extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }
}
