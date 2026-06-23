<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'operational_days' => 'array',
        'closed_dates' => 'array',
    ];
}
