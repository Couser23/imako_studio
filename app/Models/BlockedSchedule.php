<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['date', 'start_time', 'end_time', 'reason'])]
class BlockedSchedule extends Model
{
    protected $casts = [
        'date' => 'date',
    ];
}
