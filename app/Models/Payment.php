<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['booking_id', 'amount', 'payment_method_id', 'payment_proof', 'status', 'verified_by', 'verified_at'])]
class Payment extends Model
{
    protected $fillable = ['booking_id', 'amount', 'payment_method_id', 'payment_proof', 'status', 'verified_by', 'verified_at'];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
