<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Ubah tipe ENUM agar menerima pegawai
DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'employee', 'pegawai', 'user') DEFAULT 'user'");

// Konversi data lama 'employee' ke 'pegawai'
DB::table('users')->where('role', 'employee')->update(['role' => 'pegawai']);

// Hapus ENUM 'employee'
DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pegawai', 'user') DEFAULT 'user'");

echo "Done\n";
