<?php

use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\Booking;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$methods = [
    ['name' => 'BCA', 'account_number' => '12345678', 'account_name' => 'Imako Studio'],
    ['name' => 'Mandiri', 'account_number' => '87654321', 'account_name' => 'Imako Studio'],
    ['name' => 'QRIS', 'account_number' => '-', 'account_name' => 'Imako Studio'],
    ['name' => 'Tunai', 'account_number' => '-', 'account_name' => 'Kasir Imako'],
];

$methodIds = [];
foreach($methods as $m) {
    $method = PaymentMethod::firstOrCreate(['name' => $m['name']], $m);
    $methodIds[] = $method->id;
}

// Re-assign today's payments to random payment methods
$todayPayments = Payment::whereDate('verified_at', Carbon::today())->get();

foreach($todayPayments as $payment) {
    $payment->payment_method_id = $methodIds[array_rand($methodIds)];
    $payment->save();
}

// Generate extra today transactions if there are less than 5 to make the scroll visible
if($todayPayments->count() < 10) {
    $u = \App\Models\User::first();
    $p1 = \App\Models\Package::first();
    
    for($i = 0; $i < (10 - $todayPayments->count()); $i++) {
        $b = Booking::create([
            'user_id' => $u->id,
            'package_id' => $p1->id,
            'booking_date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'completed'
        ]);
        
        Payment::create([
            'booking_id' => $b->id,
            'amount' => $p1->price,
            'payment_method_id' => $methodIds[array_rand($methodIds)],
            'status' => 'verified',
            'verified_at' => Carbon::today()->format('Y-m-d H:i:s'),
            'verified_by' => $u->id
        ]);
    }
}

echo "Payment methods and transactions updated!\n";
