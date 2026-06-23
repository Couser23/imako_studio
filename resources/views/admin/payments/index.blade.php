@extends('layouts.admin')

@section('title', 'Validasi Pembayaran')
@section('pre-title', 'Transaksi')
@section('subtitle', 'Verifikasi bukti transfer dari pelanggan')

@section('content')
<!-- <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <h2 class="text-3xl font-extrabold text-brand-dark tracking-tight">Validasi Pembayaran</h2>
</div> -->

<!-- 5 Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <!-- Card 1 -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-xs font-bold text-gray-400 mb-1">Menunggu validasi</p>
        <h3 class="text-2xl font-black text-yellow-500 mb-1">{{ $stats['pending'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Perlu ditindak lanjuti</p>
    </div>
    <!-- Card 2 -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-xs font-bold text-gray-400 mb-1">Dikonfirmasi hari ini</p>
        <h3 class="text-2xl font-black text-green-500 mb-1">{{ $stats['verified_today'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Pembayaran valid</p>
    </div>
    <!-- Card 3 -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-xs font-bold text-gray-400 mb-1">Ditolak hari ini</p>
        <h3 class="text-2xl font-black text-red-500 mb-1">{{ $stats['rejected_today'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Bukti tidak valid</p>
    </div>
    <!-- Card 4 -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-xs font-bold text-gray-400 mb-1">Total masuk bulan ini</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1">{{ $stats['total_this_month'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Semua status</p>
    </div>
    <!-- Card 5 -->
    <a href="{{ route('admin.payment-methods.index') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center hover:shadow-md hover:border-blue-100 transition cursor-pointer group">
        <p class="text-xs font-bold text-gray-400 mb-1 group-hover:text-brand-primary transition">Metode pembayaran</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1 group-hover:text-brand-primary transition">{{ $stats['payment_methods_count'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Semua metode</p>
    </a>
</div>

<!-- Main Section -->
<div class="bg-white rounded-t-3xl border-t border-x border-gray-50 min-h-screen">
    
    <!-- Tabs Navigation -->
    <div class="flex items-center gap-6 px-8 pt-6 border-b border-gray-100 overflow-x-auto hide-scrollbar">
        <a href="{{ route('admin.payments.index', ['tab' => 'menunggu']) }}" class="pb-3 border-b-2 {{ $tab === 'menunggu' ? 'border-brand-dark text-brand-dark font-extrabold' : 'border-transparent text-gray-400 hover:text-brand-dark font-bold' }} text-sm flex items-center gap-2 whitespace-nowrap transition">
            Menunggu <span class="{{ $tab === 'menunggu' ? 'bg-brand-dark' : 'bg-blue-300' }} text-white text-[9px] px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
        </a>
        <a href="{{ route('admin.payments.index', ['tab' => 'konfirmasi']) }}" class="pb-3 border-b-2 {{ $tab === 'konfirmasi' ? 'border-brand-dark text-brand-dark font-extrabold' : 'border-transparent text-gray-400 hover:text-brand-dark font-bold' }} text-sm flex items-center gap-2 whitespace-nowrap transition">
            Dikonfirmasi <span class="{{ $tab === 'konfirmasi' ? 'bg-brand-dark' : 'bg-blue-300' }} text-white text-[9px] px-2 py-0.5 rounded-full">{{ $stats['verified_all'] }}</span>
        </a>
        <a href="{{ route('admin.payments.index', ['tab' => 'ditolak']) }}" class="pb-3 border-b-2 {{ $tab === 'ditolak' ? 'border-brand-dark text-brand-dark font-extrabold' : 'border-transparent text-gray-400 hover:text-brand-dark font-bold' }} text-sm flex items-center gap-2 whitespace-nowrap transition">
            Ditolak <span class="{{ $tab === 'ditolak' ? 'bg-brand-dark' : 'bg-blue-300' }} text-white text-[9px] px-2 py-0.5 rounded-full">{{ $stats['rejected_all'] }}</span>
        </a>
        <a href="{{ route('admin.payments.index', ['tab' => 'semua']) }}" class="pb-3 border-b-2 {{ $tab === 'semua' ? 'border-brand-dark text-brand-dark font-extrabold' : 'border-transparent text-gray-400 hover:text-brand-dark font-bold' }} text-sm flex items-center gap-2 whitespace-nowrap transition">
            Semua <span class="{{ $tab === 'semua' ? 'bg-brand-dark' : 'bg-blue-300' }} text-white text-[9px] px-2 py-0.5 rounded-full">{{ $stats['total_all'] }}</span>
        </a>
    </div>

    <!-- Filter & Actions Bar -->
    <form id="filter-form" method="GET" action="{{ route('admin.payments.index') }}" class="p-6 flex flex-wrap items-center gap-4 border-b border-gray-50">
        <input type="hidden" name="tab" value="{{ $tab }}">
        
        <!-- Hidden Submit Button to allow 'Enter' key submission -->
        <button type="submit" class="hidden"></button>

        <!-- Search Input -->
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / No. Pesanan" class="bg-blue-50/50 border border-blue-100 text-gray-600 text-xs font-bold rounded-full focus:border-brand-primary focus:ring-1 focus:ring-brand-primary block w-full pl-9 py-2 transition-all outline-none placeholder-gray-400" autocomplete="off" autofocus>
        </div>
        
        <!-- Dropdown Semua Paket -->
        <div class="relative">
            <select name="package_id" class="appearance-none bg-blue-50/50 border border-blue-100 text-brand-dark text-xs font-bold rounded-full pl-5 pr-10 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer">
                <option value="">Semua paket</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </span>
        </div>
        
        <!-- Date Picker -->
        <div class="relative">
            <input type="text" id="date-filter" name="date" value="{{ request('date') }}" placeholder="Pilih Tanggal" class="bg-blue-50/50 border border-blue-100 text-brand-dark text-xs font-bold rounded-full pl-5 pr-10 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer w-36">
            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </span>
        </div>
        
        <!-- Clear Filter (Only show if any filter is active) -->
        @if(request('search') || request('package_id') || request('date'))
        <a href="{{ route('admin.payments.index', ['tab' => $tab]) }}" class="text-xs font-bold text-red-500 hover:text-red-600 ml-auto transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Reset
        </a>
        @endif
    </form>

    <div id="payments-list-container" class="transition-opacity duration-300">
        <!-- Table -->
        <div class="overflow-x-auto p-6 pt-0">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100 text-brand-dark text-xs font-extrabold">
                    <th class="py-4 px-2 whitespace-nowrap">Klien / No. Pesanan</th>
                    <th class="py-4 px-2 whitespace-nowrap">Paket</th>
                    <th class="py-4 px-2 whitespace-nowrap">Jadwal / Sesi</th>
                    <th class="py-4 px-2 whitespace-nowrap">Nominal</th>
                    <th class="py-4 px-2 whitespace-nowrap">Metode</th>
                    <th class="py-4 px-2 whitespace-nowrap">Bukti</th>
                    <th class="py-4 px-2 whitespace-nowrap">Diunggah</th>
                    <th class="py-4 px-2 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold text-gray-500">
                @forelse($payments as $payment)
                <!-- Row -->
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                    <td class="py-4 px-2">
                        <p class="text-brand-dark font-extrabold text-sm mb-0.5">{{ $payment->booking?->user?->name ?? 'User' }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">#IMK-{{ str_pad($payment->booking_id, 3, '0', STR_PAD_LEFT) }}</p>
                    </td>
                    <td class="py-4 px-2">
                        <p class="text-gray-600">{{ $payment->booking?->package?->category?->name ?? 'Paket' }} -</p>
                        <p class="text-gray-600">{{ $payment->booking?->package?->name ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-2">
                        <p class="text-gray-600">{{ $payment->booking?->booking_date ? $payment->booking?->booking_date->format('d-M-Y') : '-' }}</p>
                        <p class="text-gray-400 font-medium">{{ $payment->booking?->start_time ? \Carbon\Carbon::parse($payment->booking->start_time)->format('H:i') : '-' }} - {{ $payment->booking?->end_time ? \Carbon\Carbon::parse($payment->booking->end_time)->format('H:i') : '-' }}</p>
                    </td>
                    <td class="py-4 px-2">
                        <p class="text-green-600 font-extrabold">{{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </td>
                    <td class="py-4 px-2">
                        <span class="inline-block bg-purple-50 text-purple-600 font-extrabold px-3 py-1 rounded text-[10px]">
                            {{ $payment->paymentMethod?->name ?? 'Transfer' }}
                        </span>
                    </td>
                    <td class="py-4 px-2">
                        <!-- Image Thumbnail -->
                        @if($payment->payment_proof)
                        <div class="w-10 h-12 bg-gray-100 border border-gray-200 rounded flex flex-col items-center justify-center gap-1 p-1 overflow-hidden cursor-pointer hover:border-brand-primary transition" onclick="openModal({{ $payment->id }})">
                            <img src="{{ asset('images/bukti_bayar/' . $payment->payment_proof) }}" class="w-full h-full object-cover rounded-sm">
                        </div>
                        @else
                        <div class="w-10 h-12 bg-gray-100 border border-gray-200 rounded flex flex-col items-center justify-center gap-1 p-1 overflow-hidden">
                           <span class="text-[8px] text-gray-400">Tidak ada bukti</span>
                        </div>
                        @endif
                    </td>
                    <td class="py-4 px-2">
                        <p class="text-gray-600">{{ $payment->created_at->format('d - M') }}</p>
                        <p class="text-gray-400 font-medium">{{ $payment->created_at->format('H.i') }}</p>
                    </td>
                    <td class="py-4 px-2">
                        @if($payment->status === 'pending')
                            <button onclick="openModal({{ $payment->id }})" class="bg-blue-100 hover:bg-blue-200 text-brand-dark font-extrabold px-6 py-2 rounded-full transition text-xs shadow-sm">
                                Detail
                            </button>
                        @elseif($payment->status === 'verified')
                            <span class="inline-block bg-green-100 text-green-700 font-extrabold px-4 py-2 rounded-full text-[10px]">
                                Disetujui
                            </span>
                        @else
                            <span class="inline-block bg-red-100 text-red-700 font-extrabold px-4 py-2 rounded-full text-[10px]">
                                Ditolak
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <!-- Empty state -->
                <tr>
                    <td colspan="8" class="py-10 text-center text-gray-400">Belum ada data pembayaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="p-6 border-t border-gray-50">
        {{ $payments->links() }}
    </div>
    @endif

</div>

@foreach($payments as $payment)
<!-- Payment Detail Modal -->
<div id="payment-modal-{{ $payment->id }}" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-all duration-300">
    
    <!-- Modal Backdrop to close -->
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeModal({{ $payment->id }})"></div>

    <!-- Modal Content -->
    <div id="payment-modal-content-{{ $payment->id }}" class="bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[640px] transform scale-95 transition-all duration-300 p-8 relative z-10 mx-4 border border-gray-50 flex flex-col max-h-[90vh]">
        
        <!-- Close Button -->
        <button onclick="closeModal({{ $payment->id }})" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 hover:text-brand-dark hover:bg-gray-100 rounded-full transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8 shrink-0">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-black text-brand-dark tracking-tight">Bukti Pembayaran</h3>
                <p class="text-xs font-bold text-gray-400">{{ $payment->booking?->user?->name ?? 'User' }} • #IMK-{{ str_pad($payment->booking_id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Scrollable Body -->
        <div class="overflow-y-auto hide-scrollbar shrink pr-2 space-y-6">
            
            <!-- Image Container -->
            @if($payment->payment_proof)
            <a href="{{ asset('images/bukti_bayar/' . $payment->payment_proof) }}" target="_blank" class="w-full bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center py-10 cursor-pointer hover:bg-blue-50/30 hover:border-blue-200 transition group relative overflow-hidden">
                <img src="{{ asset('images/bukti_bayar/' . $payment->payment_proof) }}" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-75 transition-opacity">
                <div class="w-16 h-16 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-300 mb-4 group-hover:scale-110 group-hover:text-blue-500 transition-all duration-300 relative z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="text-sm font-extrabold text-brand-dark mb-1 relative z-10 bg-white/80 px-2 py-0.5 rounded">Lihat Lampiran Struk</h4>
                <p class="text-[10px] font-bold text-gray-600 relative z-10 bg-white/80 px-2 py-0.5 rounded">Klik untuk memperbesar gambar bukti transfer</p>
            </a>
            @else
            <div class="w-full bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center py-10 cursor-not-allowed group relative">
                <div class="w-16 h-16 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-300 mb-4 transition-all duration-300 relative z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h4 class="text-sm font-extrabold text-brand-dark mb-1 relative z-10">Tidak Ada Bukti</h4>
            </div>
            @endif

            <!-- Details List -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
                <!-- Row 1 -->
                <div class="flex items-center justify-between p-4 border-b border-gray-50">
                    <span class="text-xs font-bold text-gray-400">Nama Paket</span>
                    <span class="text-xs font-extrabold text-brand-dark">{{ $payment->booking?->package?->category?->name ?? 'Paket' }} – {{ $payment->booking?->package?->name ?? '-' }}</span>
                </div>
                <!-- Row 2 -->
                <div class="flex items-center justify-between p-4 border-b border-gray-50">
                    <span class="text-xs font-bold text-gray-400">Nominal Transfer</span>
                    <span class="text-sm font-black text-green-600 bg-green-50 px-3 py-1 rounded-full">Rp. {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <!-- Row Metode -->
                <div class="flex items-center justify-between p-4 border-b border-gray-50">
                    <span class="text-xs font-bold text-gray-400">Metode Pembayaran</span>
                    <span class="text-xs font-extrabold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">{{ $payment->paymentMethod?->name ?? 'Transfer' }}</span>
                </div>
                <!-- Row 3 -->
                <div class="flex items-center justify-between p-4 border-b border-gray-50 bg-gray-50/30">
                    <span class="text-xs font-bold text-gray-400">Jadwal Sesi</span>
                    <span class="text-xs font-extrabold text-brand-dark">{{ $payment->booking?->booking_date ? $payment->booking->booking_date->format('d M Y') : '-' }} <span class="text-gray-300 mx-1">•</span> {{ $payment->booking?->start_time ? \Carbon\Carbon::parse($payment->booking->start_time)->format('H:i') : '-' }} - {{ $payment->booking?->end_time ? \Carbon\Carbon::parse($payment->booking->end_time)->format('H:i') : '-' }}</span>
                </div>
                <!-- Row 4 -->
                <div class="flex items-center justify-between p-4">
                    <span class="text-xs font-bold text-gray-400">Waktu Unggah</span>
                    <span class="text-xs font-extrabold text-brand-dark">{{ $payment->created_at->format('d M Y') }} <span class="text-gray-300 mx-1">•</span> {{ $payment->created_at->format('H:i') }} WIB</span>
                </div>
            </div>
            
        </div>

        <!-- Action Buttons (Sticky at bottom) -->
        <div class="pt-6 mt-2 border-t border-gray-100 flex items-center justify-between shrink-0">
            <p class="text-[9px] font-bold text-gray-400 max-w-[200px] leading-relaxed hidden sm:block">Periksa kesesuaian nominal dan nama pengirim sebelum mengonfirmasi.</p>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST" class="flex-1 sm:flex-none">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="w-full px-6 py-3 bg-white border border-red-200 text-red-500 hover:bg-red-50 text-xs font-extrabold rounded-xl transition flex items-center justify-center gap-2">
                        Tolak Validasi
                    </button>
                </form>
                
                <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST" class="flex-1 sm:flex-none">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="verified">
                    <button type="submit" class="w-full px-8 py-3 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Konfirmasi
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endforeach
    </div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#date-filter", {
            locale: "id",
            dateFormat: "Y-m-d", // Value sent to the server
            altInput: true,
            altFormat: "d - M - Y", // Display format: 16 - Mei - 2026
            onChange: function(selectedDates, dateStr, instance) {
                // Dispatch input event to trigger AJAX
                document.getElementById('date-filter').dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filter-form');
        const container = document.getElementById('payments-list-container');
        let debounceTimer;

        function fetchResults() {
            const url = new URL(filterForm.action);
            const formData = new FormData(filterForm);
            for (const [key, value] of formData.entries()) {
                if(value) url.searchParams.append(key, value);
            }

            // Update URL bar without reloading
            window.history.pushState({}, '', url);

            container.style.opacity = '0.5';

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById('payments-list-container');
                    
                    if (newContainer) {
                        container.innerHTML = newContainer.innerHTML;
                    }
                    container.style.opacity = '1';
                })
                .catch(() => {
                    container.style.opacity = '1';
                });
        }

        // Intercept native form submit
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchResults();
        });

        // Trigger fetch on input/change with debounce
        filterForm.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchResults();
                }, 400); // 400ms delay for smooth typing
            });
        });
    });
</script>
<script>
    function openModal(id) {
        const modal = document.getElementById('payment-modal-' + id);
        const modalContent = document.getElementById('payment-modal-content-' + id);
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95', 'translate-y-4');
        modalContent.classList.add('scale-100', 'translate-y-0');
    }

    function closeModal(id) {
        const modal = document.getElementById('payment-modal-' + id);
        const modalContent = document.getElementById('payment-modal-content-' + id);
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100', 'translate-y-0');
        modalContent.classList.add('scale-95', 'translate-y-4');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // match duration-300
    }
</script>
@endpush
@endsection
