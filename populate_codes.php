<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bookings = \App\Models\Booking::all();
foreach($bookings as $b) {
    do {
        $code = strtoupper(Illuminate\Support\Str::random(6));
    } while (\App\Models\Booking::where('booking_code', $code)->exists());
    $b->update(['booking_code' => $code]);
    echo "Updated Booking {$b->id} with code {$code}\n";
}
