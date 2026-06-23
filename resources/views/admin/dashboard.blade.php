@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Top Stats Row (5 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        
        <!-- Stat Card 1: Pendapatan Bulan Ini -->
        <div class="bg-white px-4 py-3.5 rounded-[16px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 text-brand-blue">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10h2v10H7zm4-5h2v15h-2zm4 7h2v8h-2z"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] sm:text-xs font-semibold text-gray-400 mb-0.5 truncate">Pendapatan Bulan ini</p>
                <h3 class="text-sm sm:text-base font-black text-brand-dark whitespace-nowrap">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Stat Card 2: Pemasukan Hari Ini -->
        <div class="bg-white px-4 py-3.5 rounded-[16px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0 text-green-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] sm:text-xs font-semibold text-gray-400 mb-0.5 truncate">Pemasukan hari ini</p>
                <h3 class="text-sm sm:text-base font-black text-brand-dark leading-tight whitespace-nowrap">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold {{ $revenueGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} truncate">{{ $revenueGrowth >= 0 ? '+' : '' }}{{ round($revenueGrowth, 1) }}% vs kemarin</p>
            </div>
        </div>

        <!-- Stat Card 3: Booking Masuk -->
        <div class="bg-white px-4 py-3.5 rounded-[16px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center shrink-0 text-yellow-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] sm:text-xs font-semibold text-gray-400 mb-0.5 truncate">Booking masuk</p>
                <h3 class="text-sm sm:text-base font-black text-brand-dark leading-tight whitespace-nowrap">{{ $newBookings }}</h3>
                <p class="text-[9px] font-bold text-yellow-600 truncate">{{ $waitingVerification }} menunggu validasi</p>
            </div>
        </div>

        <!-- Stat Card 4: Total Pegawai -->
        <div class="bg-white px-4 py-3.5 rounded-[16px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center shrink-0 text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] sm:text-xs font-semibold text-gray-400 mb-0.5 truncate">Total Pegawai</p>
                <h3 class="text-sm sm:text-base font-black text-brand-dark whitespace-nowrap">{{ $totalEmployees }}</h3>
            </div>
        </div>

        <!-- Stat Card 5: Total Pengguna -->
        <div class="bg-white px-4 py-3.5 rounded-[16px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center shrink-0 text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] sm:text-xs font-semibold text-gray-400 mb-0.5 truncate">Total Pengguna</p>
                <h3 class="text-sm sm:text-base font-black text-brand-dark whitespace-nowrap">{{ $totalUsers }}</h3>
            </div>
        </div>

    </div>

    <!-- Middle Row (2 Tables) -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        
        <!-- Jadwal Sesi Table -->
        <div class="lg:col-span-3 bg-white rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="p-6 pb-2">
                <h3 class="text-lg font-bold text-brand-dark">Jadwal sesi hari ini</h3>
            </div>
            <div class="overflow-auto max-h-[350px] px-4 pb-4 custom-scrollbar">
                <table class="w-full text-left relative">
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wide border-b border-gray-100">
                            <th class="px-4 py-3 pb-4 bg-white">Jam</th>
                            <th class="px-4 py-3 pb-4 bg-white">Klien</th>
                            <th class="px-4 py-3 pb-4 bg-white">Paket</th>
                            <th class="px-4 py-3 pb-4 bg-white">Fotografer</th>
                            <th class="px-4 py-3 pb-4 bg-white text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-brand-dark">
                        @forelse($todaySchedules as $schedule)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="px-4 py-4">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                            <td class="px-4 py-4">{{ $schedule->user->name ?? 'Guest' }}</td>
                            <td class="px-4 py-4 text-gray-500">{{ $schedule->package->name ?? 'Paket Kustom' }}</td>
                            <td class="px-4 py-4">
                                @if($schedule->assignments->count() > 0)
                                    {{ $schedule->assignments->pluck('employee.name')->join(', ') }}
                                @else
                                    <span class="text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                @php
                                    $statusConfig = [
                                        'pending' => ['text' => 'Menunggu', 'class' => 'bg-gray-100 text-gray-600'],
                                        'waiting_payment' => ['text' => 'Belum Bayar', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'payment_uploaded' => ['text' => 'Verifikasi', 'class' => 'bg-blue-100 text-blue-700'],
                                        'confirmed' => ['text' => 'Dikonfirmasi', 'class' => 'bg-indigo-100 text-indigo-700'],
                                        'in_progress' => ['text' => 'Berlangsung', 'class' => 'bg-[#84cc16] text-white'],
                                        'completed' => ['text' => 'Selesai', 'class' => 'bg-green-100 text-green-700'],
                                        'cancelled' => ['text' => 'Batal', 'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $conf = $statusConfig[$schedule->status] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-600'];
                                @endphp
                                <span class="{{ $conf['class'] }} px-3 py-1 rounded-full text-[10px] font-bold shadow-sm">{{ $conf['text'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-xs">Belum ada jadwal sesi untuk hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menunggu Validasi Table -->
        <div class="lg:col-span-2 bg-white rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="p-6 pb-2">
                <h3 class="text-lg font-bold text-brand-dark">Menunggu validasi</h3>
            </div>
            <div class="overflow-auto max-h-[350px] px-4 pb-4 custom-scrollbar">
                <table class="w-full text-left relative">
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wide border-b border-gray-100">
                            <th class="px-4 py-3 pb-4 bg-white">Nama</th>
                            <th class="px-4 py-3 pb-4 bg-white">Paket</th>
                            <th class="px-4 py-3 pb-4 bg-white">Tanggal</th>
                            <th class="px-4 py-3 pb-4 bg-white">Jam</th>
                            <th class="px-4 py-3 pb-4 bg-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-brand-dark">
                        @forelse($recentBookings as $booking)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="px-4 py-4">{{ $booking->user->name ?? 'Guest' }}</td>
                            <td class="px-4 py-4 text-gray-500">{{ $booking->package->name ?? 'Paket Kustom' }}</td>
                            <td class="px-4 py-4">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-4">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-full text-[10px] font-bold shadow-sm transition inline-block">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-xs">Belum ada pesanan terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bottom Row (3 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Pendapatan Bulan Ini Chart -->
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col">
            <h3 class="text-lg font-bold text-brand-dark mb-4">Pendapatan bulan ini</h3>
            <div class="mb-6">
                <h2 class="text-2xl font-black text-brand-dark">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h2>
                <p class="text-xs font-bold text-green-500 mt-1">Total pembayaran diverifikasi</p>
            </div>
            <!-- Dynamic CSS Bar Chart -->
            <div class="flex-1 flex items-end justify-between gap-2 mt-auto h-32 pt-4">
                @php
                    $maxRev = max(array_column($last7DaysRevenue, 'amount')) ?: 1;
                @endphp
                @foreach(array_reverse($last7DaysRevenue) as $rev)
                    @php
                        $height = ($rev['amount'] / $maxRev) * 100;
                        if ($height < 10) $height = 10; // Minimum height for visibility
                    @endphp
                    <div class="w-full flex flex-col justify-end h-full" title="Rp {{ number_format($rev['amount'], 0, ',', '.') }}">
                        <div class="w-full bg-brand-blue rounded-t-md transition-all duration-300" style="height: {{ $height }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2 text-[8px] font-bold text-gray-400">
                @foreach(array_reverse($last7DaysRevenue) as $rev)
                    <span class="w-full text-center">{{ $rev['day'] }}</span>
                @endforeach
            </div>
            <p class="text-[10px] text-gray-400 font-bold mt-1 text-center">7 Hari terakhir</p>
        </div>

        <!-- Booking Per Paket -->
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
            <h3 class="text-lg font-bold text-brand-dark mb-6">Booking per paket (bulan ini)</h3>
            <div class="space-y-5">
                @forelse($packageBookings as $pb)
                    @php
                        $width = ($pb->bookings_count / $maxPackageBookings) * 100;
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-500 w-24 truncate" title="{{ $pb->name }}">{{ $pb->name }}</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full ml-4 overflow-hidden">
                            <div class="h-full bg-brand-blue rounded-full transition-all duration-300" style="width: {{ $width }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-400 ml-3">{{ $pb->bookings_count }}</span>
                    </div>
                @empty
                    <p class="text-center text-xs text-gray-400 py-4">Belum ada booking bulan ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Status Pegawai -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="p-6 pb-2">
                <h3 class="text-lg font-bold text-brand-dark">Status pegawai hari ini</h3>
            </div>
            <div class="overflow-x-auto p-4">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wide border-b border-gray-100">
                            <th class="px-4 py-2 pb-4">Nama</th>
                            <th class="px-4 py-2 pb-4">Jam</th>
                            <th class="px-4 py-2 pb-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-brand-dark">
                        @forelse($employeeStatuses as $empStatus)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                                <td class="px-4 py-4">{{ $empStatus->name }}</td>
                                <td class="px-4 py-4 text-gray-400">{{ $empStatus->timeSlot }} <span class="font-normal text-gray-400">{{ $empStatus->sessionCount > 0 ? $empStatus->sessionCount . ' Sesi' : '' }}</span></td>
                                <td class="px-4 py-4 text-right">
                                    <span class="{{ $empStatus->statusClass }} px-3 py-1 rounded-full text-[10px] font-bold shadow-sm">{{ $empStatus->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-xs">Belum ada pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
