<x-mail::message>
# Laporan {{ ucfirst($reportData['frequency'] ?? 'Harian') }} Imako Studio

Halo Admin, berikut adalah ringkasan performa dan pemasukan studio Anda untuk periode ini:

**Total Pendapatan:** Rp {{ number_format($reportData['total_revenue'] ?? 0, 0, ',', '.') }}  
**Total Booking Baru:** {{ $reportData['total_bookings'] ?? 0 }} sesi  
**Pembayaran Menunggu Validasi:** {{ $reportData['pending_payments'] ?? 0 }} transaksi  

<x-mail::button :url="route('admin.dashboard')">
Lihat Dashboard Lengkap
</x-mail::button>

Tetap semangat dan sukses selalu!<br>
{{ config('app.name') }}
</x-mail::message>
