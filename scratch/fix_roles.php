<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Ubah tipe ENUM
DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'employee', 'pegawai', 'user') DEFAULT 'user'");

// Konversi data lama 'pegawai' ke 'employee'
DB::table('users')->where('role', 'pegawai')->update(['role' => 'employee']);

// Hapus ENUM 'pegawai'
DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'employee', 'user') DEFAULT 'user'");

echo "Done\n";
