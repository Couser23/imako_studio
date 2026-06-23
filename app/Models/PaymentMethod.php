<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'provider', 'account_number', 'account_name', 'logo', 'is_active'])]
class PaymentMethod extends Model
{
}
