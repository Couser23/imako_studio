<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\Package;
use App\Models\Category;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Expense;

class FinanceTestSeeder extends Seeder
{
    public function run()
    {
        $u = User::first();
        $pm = PaymentMethod::firstOrCreate([
            'name' => 'BCA', 
            'account_number' => '12345678', 
            'account_name' => 'Imako Studio'
        ]);
        
        $p1 = Package::first();
        $p2 = Package::skip(1)->first();
        
        $c = Category::firstOrCreate(['name' => 'Test', 'slug' => 'test']);
        
        if(!$p1) {
            $p1 = Package::create(['name' => 'Basic', 'description' => 'T', 'price' => 500000, 'duration_minutes' => 60, 'category_id' => $c->id]);
        }
        
        if(!$p2) {
            $p2 = Package::create(['name' => 'Pro', 'description' => 'T', 'price' => 1000000, 'duration_minutes' => 120, 'category_id' => $c->id]);
        }
        
        for($i = 1; $i <= 30; $i++) {
            $date = now()->subDays(rand(0, 30))->format('Y-m-d');
            $b = Booking::create([
                'user_id' => $u->id,
                'package_id' => $i % 2 == 0 ? $p2->id : $p1->id,
                'booking_date' => $date,
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'status' => 'completed'
            ]);
            
            Payment::create([
                'booking_id' => $b->id,
                'amount' => $i % 2 == 0 ? $p2->price : $p1->price,
                'payment_method_id' => $pm->id,
                'status' => 'verified',
                'verified_at' => $date . ' 12:00:00',
                'verified_by' => $u->id
            ]);
        }
        
        Expense::create(['expense_date' => now()->subDays(2), 'category' => 'Operasional', 'amount' => 50000, 'notes' => 'Beli Kopi']);
        Expense::create(['expense_date' => now()->subDays(5), 'category' => 'Peralatan', 'amount' => 1500000, 'notes' => 'Sewa Lensa Tambahan']);
        Expense::create(['expense_date' => now()->subDays(10), 'category' => 'Tagihan', 'amount' => 500000, 'notes' => 'Listrik Studio']);
        
        echo "Data Seeded!\n";
    }
}
