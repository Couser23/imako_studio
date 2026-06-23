@extends('layouts.admin')

@section('title', 'Semua Jadwal Imako Studio')
@section('pre-title', 'Reservasi')
@section('subtitle', 'Daftar semua jadwal booking pelanggan')

@section('content')

<!-- 5 Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Sesi Hari ini</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1">{{ $stats['today'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">09.00 - 18.00</p>
    </div>
    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Berlangsung</p>
        <h3 class="text-2xl font-black text-green-500 mb-1">{{ $stats['ongoing'] }}</h3>
        <p class="text-[10px] font-bold text-green-500">Sedang berjalan</p>
    </div>
    <!-- Card 3 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Bulan ini</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1">{{ $stats['this_month'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Total booking</p>
    </div>
    <!-- Card 4 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Belum assign</p>
        <h3 class="text-2xl font-black text-yellow-500 mb-1">{{ $stats['unassigned'] }}</h3>
        <p class="text-[10px] font-bold text-yellow-500">Perlu Fotografer</p>
    </div>
    <!-- Card 5 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Slot kosong hari ini</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1">{{ $stats['slots'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Bisa dibooking</p>
    </div>
</div>

<!-- Controls Bar -->
<form id="schedule-filter-form" method="GET" action="{{ route('admin.schedules.index') }}" class="flex flex-col xl:flex-row items-center justify-between gap-4 mb-4">
    <!-- Keep view type -->
    <input type="hidden" name="view" value="{{ $view }}">
    <input type="hidden" name="month" value="{{ $currentDate->month }}">
    <input type="hidden" name="year" value="{{ $currentDate->year }}">
    <input type="hidden" name="date" id="filter-date" value="{{ request('date') }}">
    
    <!-- Left Controls -->
    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
        <!-- Date Navigator -->
        <div class="flex items-center bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm shrink-0">
            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year])) }}" class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 transition border-r border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="px-4 py-1.5 text-xs font-extrabold text-brand-dark bg-white whitespace-nowrap">{{ $currentDate->translatedFormat('F Y') }}</span>
            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year])) }}" class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 transition border-l border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <a href="{{ route('admin.schedules.index', ['view' => $view]) }}" class="px-4 py-1.5 bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg shadow-sm hover:bg-gray-50 transition whitespace-nowrap">
            Bulan ini
        </a>
        
        <div class="w-px h-6 bg-gray-300 mx-1 hidden sm:block"></div>

        <!-- Filters -->
        <div class="relative w-full sm:w-auto">
            <select name="package_id" class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg pl-4 pr-8 py-1.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                <option value="">Semua paket</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </span>
        </div>
        <div class="relative w-full sm:w-auto">
            <select name="employee_id" class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg pl-4 pr-8 py-1.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                <option value="">Semua fotografer</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </span>
        </div>
        <!-- Search -->
        <div class="relative w-full sm:w-48 xl:w-64">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari klien atau #IMK..." class="bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg focus:border-brand-primary focus:ring-1 focus:ring-brand-primary block w-full pl-9 py-1.5 transition-all outline-none placeholder-gray-400 shadow-sm" autocomplete="off">
        </div>

        <div class="relative w-full sm:w-auto">
            <select name="status" class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg pl-4 pr-8 py-1.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                <option value="">Semua status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Berlangsung</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <span class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </span>
        </div>
        
        @if(request('package_id') || request('employee_id') || request('status') || request('search') || request('date'))
        <a href="{{ route('admin.schedules.index', ['view' => $view]) }}" class="text-xs font-bold text-red-500 hover:text-red-600 transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Reset
        </a>
        @endif
    </div>

    <!-- Right Controls -->
    <div class="flex items-center gap-3">
        <!-- Toggles -->
        <div class="flex items-center bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <button type="button" id="btn-kalender" onclick="switchView('kalender')" class="w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white border-r border-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Kalender
            </button>
            <button type="button" id="btn-timeline" onclick="switchView('timeline')" class="w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark hover:bg-gray-50 transition border-r border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                Timeline
            </button>
            <button type="button" id="btn-list" onclick="switchView('list')" class="w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                List
            </button>
        </div>
        
        <button type="button" onclick="openBlokirModal()" class="px-5 py-1.5 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Blokir Slot
        </button>
    </div>
</form>


<div id="schedules-data-container" class="transition-opacity duration-300">

<!-- Legend -->
<div class="flex items-center justify-end gap-4 mb-2 pr-2">
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 bg-blue-100 border border-blue-200 rounded-sm"></div>
        <span class="text-[10px] font-bold text-gray-500">Sesi</span>
    </div>
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 bg-yellow-50 border border-yellow-300 border-dashed rounded-sm"></div>
        <span class="text-[10px] font-bold text-gray-500">Jeda otomatis</span>
    </div>
    <div class="flex items-center gap-1.5">
        <div class="w-3 h-3 bg-gray-100 border border-gray-200 rounded-sm"></div>
        <span class="text-[10px] font-bold text-gray-500">Diblokir</span>
    </div>
</div>

<!-- ================= KALENDER VIEW ================= -->
<div id="view-kalender" class="{{ ($view ?? 'list') === 'kalender' ? 'block' : 'hidden' }}">
<!-- Calendar Grid -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col min-h-[700px]">
    
    <!-- Days Header -->
    <div class="grid grid-cols-7 border-b border-gray-200 bg-white shrink-0">
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">MIN</div>
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">SEN</div>
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">SEL</div>
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">RAB</div>
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">KAM</div>
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">JUM</div>
        <div class="py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">SAB</div>
    </div>

    <!-- Calendar Body -->
    <div class="flex-1 grid grid-cols-7 bg-gray-100 gap-px">
        @foreach($calendar as $cell)
        <div class="{{ $cell['date']->isToday() ? 'bg-blue-50/40 border-[1.5px] border-brand-dark rounded' : ($cell['is_current_month'] ? 'bg-white' : 'bg-gray-50/50') }} p-2 min-h-[100px] flex flex-col gap-1.5 relative z-10 shadow-sm">
            <span class="text-[11px] {{ $cell['date']->isToday() ? 'font-extrabold text-white bg-brand-dark w-5 h-5 rounded-full flex items-center justify-center mb-1' : ($cell['is_current_month'] ? 'font-bold text-brand-dark' : 'font-bold text-gray-300') }}">
                {{ $cell['day'] }}
            </span>
            
            @foreach($cell['events']->take(3) as $event)
            <!-- Event block -->
            <div class="px-2 py-1 bg-blue-100 text-blue-700 text-[9px] font-bold rounded cursor-pointer hover:bg-blue-200 transition truncate" onclick="openDetailModal({{ $event->id }})">
                {{ \Carbon\Carbon::parse($event->start_time)->format('H.i') }} {{ $event->user->name ?? 'Pelanggan' }} - {{ $event->package->name ?? '-' }}
            </div>
            @endforeach
            
            @if(count($cell['events']) > 3)
            <a href="javascript:void(0)" onclick="filterByDate('{{ $cell['date']->format('Y-m-d') }}')" class="text-[9px] text-gray-400 font-bold mt-1 hover:text-brand-primary transition block">+{{ count($cell['events']) - 3 }} lainnya</a>
            @endif
        </div>
        @endforeach
    </div>
</div>
</div> <!-- End of view-kalender -->

<!-- ================= TIMELINE VIEW (Pulsing Dots) ================= -->
<div id="view-timeline" class="{{ ($view ?? 'list') === 'timeline' ? 'block' : 'hidden' }}">
    <div class="relative bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 sm:p-10 overflow-hidden">
        <!-- Vertical line connecting timeline -->
        <div class="absolute left-16 sm:left-[120px] top-10 bottom-10 w-0.5 bg-gray-100 hidden sm:block"></div>

        <div class="space-y-8 relative z-10">
            @forelse($todayBookings as $booking)
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-10">
                <div class="w-24 shrink-0 flex flex-col items-start sm:items-end pt-2">
                    <h4 class="text-xl font-black text-brand-dark">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</h4>
                    <p class="text-[10px] font-bold text-gray-400">s/d {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
                </div>
                <div class="hidden sm:flex relative items-center justify-center shrink-0 w-8 h-8 -ml-4 z-10">
                    @if($booking->status == 'in_progress')
                        <div class="w-3 h-3 bg-brand-primary rounded-full shadow-[0_0_0_4px_rgba(255,255,255,1)]"></div>
                        <div class="absolute w-3 h-3 bg-brand-primary rounded-full animate-ping opacity-75"></div>
                    @else
                        <div class="w-3 h-3 bg-gray-300 rounded-full shadow-[0_0_0_4px_rgba(255,255,255,1)]"></div>
                    @endif
                </div>
                <div class="flex-1 bg-white border {{ $booking->status == 'in_progress' ? 'border-brand-primary/20' : 'border-gray-100' }} shadow-sm rounded-2xl p-5 hover:border-brand-primary transition cursor-pointer relative overflow-hidden" onclick="openDetailModal()">
                    @if($booking->status == 'in_progress')
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-brand-primary"></div>
                    @endif
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                @if($booking->status == 'pending')
                                    <span class="px-2.5 py-1 bg-yellow-50 text-yellow-500 text-[9px] font-extrabold rounded-full">Menunggu</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-600 text-[9px] font-extrabold rounded-full">Dikonfirmasi</span>
                                @elseif($booking->status == 'in_progress')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full">Berlangsung</span>
                                @elseif($booking->status == 'completed')
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-[9px] font-extrabold rounded-full">Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-600 text-[9px] font-extrabold rounded-full">{{ ucfirst($booking->status) }}</span>
                                @endif
                                <span class="text-xs font-bold text-gray-500">{{ $booking->user->name ?? 'Pelanggan' }}</span>
                            </div>
                            <h4 class="text-lg font-extrabold text-brand-dark mb-1">{{ $booking->package->name ?? 'Paket' }}</h4>
                        </div>
                        <div class="flex items-center gap-6 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Fotografer</span>
                                <div class="flex items-center gap-2">
                                    @if($booking->assignments && $booking->assignments->count() > 0)
                                        <div class="flex items-center -space-x-2 mr-2">
                                        @foreach($booking->assignments as $assignment)
                                            @php
                                                $employee = $assignment->employee;
                                                $employeeName = $employee->name ?? 'Pegawai';
                                                $initial = substr($employeeName, 0, 1);
                                            @endphp
                                            @if($employee && $employee->avatar)
                                            <div class="w-6 h-6 rounded-full overflow-hidden border-2 border-white flex items-center justify-center bg-white shrink-0 relative z-[{{ $loop->iteration }}]" title="{{ $employeeName }}">
                                                <img src="{{ asset('images/profile_akun/' . strtolower($employee->role ?? 'user') . '/' . $employee->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                            </div>
                                            @else
                                            <div class="w-6 h-6 rounded-full border-2 border-white bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-black shrink-0 relative z-[{{ $loop->iteration }}]" title="{{ $employeeName }}">{{ strtoupper($initial) }}</div>
                                            @endif
                                        @endforeach
                                        </div>
                                        <span class="text-sm font-bold text-brand-dark">
                                            {{ $booking->assignments->map(fn($a) => explode(' ', $a->employee->name ?? 'Pegawai')[0])->join(', ') }}
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-yellow-600">Belum di assign</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-10 text-center">
                <p class="text-gray-400 font-bold text-sm">Tidak ada jadwal untuk hari ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ================= LIST VIEW (Table) ================= -->
<div id="view-list" class="{{ ($view ?? 'list') === 'list' ? 'block' : 'hidden' }}">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Table Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-b border-gray-200 gap-4">
            <h3 class="text-sm font-extrabold text-brand-dark">Semua Booking — {{ $currentDate->translatedFormat('F Y') }}</h3>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold rounded-lg shadow-sm flex items-center gap-2 transition whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/30 text-gray-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-3 px-6 whitespace-nowrap">Klien</th>
                        <th class="py-3 px-6 whitespace-nowrap">Paket</th>
                        <th class="py-3 px-6 whitespace-nowrap">Tanggal & Jam</th>
                        <th class="py-3 px-6 whitespace-nowrap">Fotografer</th>
                        <th class="py-3 px-6 whitespace-nowrap">Nominal</th>
                        <th class="py-3 px-6 whitespace-nowrap">Status</th>
                        <th class="py-3 px-6 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-bold text-gray-600">
                    
                    @forelse($listBookings as $booking)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6">
                            <p class="text-brand-dark font-extrabold mb-0.5">{{ $booking->user->name ?? 'Pelanggan' }}</p>
                            <p class="text-[10px] text-gray-400">#IMK-{{ $booking->booking_code }}</p>
                        </td>
                        <td class="py-4 px-6">{{ $booking->package->name ?? 'Paket' }}</td>
                        <td class="py-4 px-6">
                            <p class="text-gray-600 mb-0.5">{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }}</p>
                            <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->start_time)->diffInMinutes(\Carbon\Carbon::parse($booking->end_time)) }} mnt</p>
                        </td>
                        <td class="py-4 px-6">
                            @if($booking->assignments && $booking->assignments->count() > 0)
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center -space-x-2">
                                        @foreach($booking->assignments as $assignment)
                                            @php
                                                $employee = $assignment->employee;
                                                $employeeName = $employee->name ?? 'Pegawai';
                                                $initial = substr($employeeName, 0, 1);
                                            @endphp
                                            @if($employee && $employee->avatar)
                                            <div class="w-6 h-6 rounded-full overflow-hidden border-2 border-white flex items-center justify-center bg-white shrink-0 relative z-[{{ $loop->iteration }}]" title="{{ $employeeName }}">
                                                <img src="{{ asset('images/profile_akun/' . strtolower($employee->role ?? 'user') . '/' . $employee->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                            </div>
                                            @else
                                            <div class="w-6 h-6 rounded-full border-2 border-white bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-black shrink-0 relative z-[{{ $loop->iteration }}]" title="{{ $employeeName }}">{{ strtoupper($initial) }}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <span class="text-brand-dark font-extrabold text-[11px] leading-tight">
                                        {!! $booking->assignments->map(fn($a) => explode(' ', $a->employee->name ?? 'Pegawai')[0])->join('<br>') !!}
                                    </span>
                                </div>
                            @else
                                <span class="px-2 py-0.5 border border-yellow-300 text-yellow-600 bg-yellow-50 text-[9px] font-extrabold rounded">Belum assign</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-green-600 font-extrabold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            @if($booking->status == 'pending')
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-500 text-[9px] font-extrabold rounded-full">Menunggu</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-600 text-[9px] font-extrabold rounded-full">Dikonfirmasi</span>
                            @elseif($booking->status == 'in_progress')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full">Berlangsung</span>
                            @elseif($booking->status == 'completed')
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-[9px] font-extrabold rounded-full">Selesai</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-600 text-[9px] font-extrabold rounded-full">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <button onclick="openDetailModal({{ $booking->id }})" class="px-4 py-1 bg-white border border-gray-200 text-gray-500 hover:text-brand-dark hover:bg-gray-50 text-[10px] font-extrabold rounded-full transition shadow-sm">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400 text-sm">Belum ada booking.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="p-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-[10px] font-bold text-gray-400">Menampilkan {{ $listBookings->firstItem() ?? 0 }}-{{ $listBookings->lastItem() ?? 0 }} dari {{ $listBookings->total() }} booking</span>
            @if($listBookings->hasPages())
            <div class="flex items-center gap-1">
                {{ $listBookings->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
<!-- Modal Blokir Slot -->
<div id="blokir-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeBlokirModal()"></div>

    <!-- Modal Content -->
    <!-- Modal Content -->
    <div id="blokir-modal-content" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[480px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col">
        <form action="{{ route('admin.schedules.block') }}" method="POST">
            @csrf
            <div class="p-8">
                <h3 class="text-lg font-black text-brand-dark mb-5 tracking-tight">Blokir Slot Manual</h3>

                <!-- Info Box -->
                <div class="flex items-start gap-3 bg-gray-50/80 p-4 rounded-xl border border-gray-100 mb-6">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="text-xs font-bold text-gray-500 leading-relaxed">Slot yang diblokir tidak akan bisa dipilih oleh pelanggan saat booking.</p>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-5 mb-6">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-xs font-extrabold text-brand-dark mb-2">Tanggal</label>
                        <div class="relative">
                            <input type="date" name="booking_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg px-3 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition">
                        </div>
                    </div>
                    
                    <!-- Jenis Paket (Maintenance) -->
                    <div class="relative">
                        <label class="block text-xs font-extrabold text-brand-dark mb-2">Jenis Penutupan</label>
                        <select name="package_id" required class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg px-3 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-8">
                            <option value="">-- Pilih --</option>
                            @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                            @endforeach
                            <!-- We can use a null package if the system supports it, but standard booking requires a package. Let's assume we use an existing package or nullable package ID in DB -->
                        </select>
                        <span class="absolute bottom-3 right-3 flex items-center pointer-events-none text-brand-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </div>
                    
                    <!-- Jam Mulai -->
                    <div>
                        <label class="block text-xs font-extrabold text-brand-dark mb-2">Jam mulai</label>
                        <input type="time" name="start_time" step="300" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg px-3 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer">
                    </div>

                    <!-- Jam Selesai -->
                    <div>
                        <label class="block text-xs font-extrabold text-brand-dark mb-2">Jam selesai</label>
                        <input type="time" name="end_time" step="300" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg px-3 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer">
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-8">
                    <label class="block text-xs font-extrabold text-brand-dark mb-2">Keterangan <span class="text-gray-400 font-medium">(Opsional)</span></label>
                    <input type="text" name="notes" placeholder="Contoh: Studio Full, Studio Maintance" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <button type="button" onclick="closeBlokirModal()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-500 hover:text-brand-dark hover:bg-gray-50 text-xs font-extrabold rounded-xl transition flex items-center justify-center">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Blokir Slot
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal Detail Sesi & Penugasan Loops -->
@foreach($bookings as $booking)
<div id="detail-modal-{{ $booking->id }}" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeDetailModal({{ $booking->id }})"></div>

    <div id="detail-modal-content-{{ $booking->id }}" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[540px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col max-h-[90vh]">
        
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md rounded-t-[24px] z-20">
            <div>
                <h3 class="text-lg font-black text-brand-dark tracking-tight">Detail Sesi Booking</h3>
                <p class="text-[11px] font-bold text-gray-400">Atur penugasan dan status untuk sesi ini.</p>
            </div>
            <button onclick="closeDetailModal({{ $booking->id }})" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            
            <div class="bg-blue-50/50 rounded-2xl p-5 mb-6 border border-blue-100/50">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-base font-black text-brand-dark">{{ $booking->user->name ?? 'Pelanggan' }}</h4>
                        <p class="text-xs font-bold text-gray-500">#IMK-{{ $booking->booking_code }}</p>
                    </div>
                    @if($booking->assignments && $booking->assignments->count() > 0)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full">Ditugaskan</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full">Belum assign</span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 mb-1">Paket</p>
                        <p class="text-xs font-bold text-brand-dark">{{ $booking->package->name ?? 'Paket' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 mb-1">Total</p>
                        <p class="text-xs font-bold text-brand-dark">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 mb-1">Tanggal</p>
                        <p class="text-xs font-bold text-brand-dark">{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 mb-1">Jam Sesi</p>
                        <p class="text-xs font-bold text-brand-dark">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Ubah Status -->
            <form action="{{ route('admin.schedules.update-status', $booking->id) }}" method="POST" class="mb-6 border-b border-gray-100 pb-6">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h4 class="text-sm font-extrabold text-brand-dark">Status Jadwal</h4>
                </div>
                <div class="flex items-end gap-3">
                    <div class="flex-1 relative">
                        <select name="status" class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-8">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <span class="absolute bottom-3.5 right-3 flex items-center pointer-events-none text-brand-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-brand-dark text-white hover:bg-gray-800 text-xs font-extrabold rounded-xl transition shadow-sm flex items-center justify-center shrink-0">
                        Update Status
                    </button>
                </div>
            </form>

            <!-- Form Penugasan & Link Foto -->
            <!-- Form Penugasan & Link Foto -->
            <form action="{{ route('admin.schedules.assign', $booking->id) }}" method="POST">
                @csrf
                <div class="flex items-center justify-between mb-4 mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <h4 class="text-sm font-extrabold text-brand-dark">Tim Fotografer</h4>
                    </div>
                    <button type="button" onclick="addEmployeeField('{{ $booking->id }}')" class="text-[10px] font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Pegawai
                    </button>
                </div>
                
                <div id="employee-container-{{ $booking->id }}" class="space-y-3">
                    @if($booking->assignments->count() > 0)
                        @foreach($booking->assignments as $assignment)
                            <div class="flex items-center gap-2 employee-row">
                                <div class="relative flex-1">
                                    <select name="employee_ids[]" onchange="updateEmployeeOptions('{{ $booking->id }}')" required class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-8">
                                        <option value="">-- Pilih Fotografer --</option>
                                        @foreach($employees as $employee)
                                            @php
                                                $isOnLeave = false;
                                                if ($booking->booking_date) {
                                                    $bDate = $booking->booking_date->format('Y-m-d');
                                                    foreach($employee->leaveRequests as $leave) {
                                                        if ($leave->status === 'approved' && $bDate >= $leave->start_date->format('Y-m-d') && $bDate <= $leave->end_date->format('Y-m-d')) {
                                                            $isOnLeave = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if(!$isOnLeave || $assignment->employee_id == $employee->id)
                                            <option value="{{ $employee->id }}" {{ $assignment->employee_id == $employee->id ? 'selected' : '' }} {{ $isOnLeave ? 'disabled' : '' }}>
                                                {{ $employee->name }} {{ $isOnLeave ? '(Sedang Cuti)' : '' }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="absolute bottom-3.5 right-3 flex items-center pointer-events-none text-brand-dark">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </span>
                                </div>
                                <button type="button" onclick="removeEmployeeField(this, '{{ $booking->id }}')" class="btn-remove-emp w-10 h-10 shrink-0 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 rounded-xl transition" {{ $booking->assignments->count() <= 1 ? 'style=display:none;' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center gap-2 employee-row">
                            <div class="relative flex-1">
                                <select name="employee_ids[]" onchange="updateEmployeeOptions('{{ $booking->id }}')" required class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-8">
                                    <option value="">-- Pilih Fotografer --</option>
                                    @foreach($employees as $employee)
                                        @php
                                            $isOnLeave = false;
                                            if ($booking->booking_date) {
                                                $bDate = $booking->booking_date->format('Y-m-d');
                                                foreach($employee->leaveRequests as $leave) {
                                                    if ($leave->status === 'approved' && $bDate >= $leave->start_date->format('Y-m-d') && $bDate <= $leave->end_date->format('Y-m-d')) {
                                                        $isOnLeave = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if(!$isOnLeave)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->name }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="absolute bottom-3.5 right-3 flex items-center pointer-events-none text-brand-dark">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </div>
                            <button type="button" onclick="removeEmployeeField(this, '{{ $booking->id }}')" class="btn-remove-emp w-10 h-10 shrink-0 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 rounded-xl transition" style="display:none;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Form Link GDrive -->
                <div class="mt-6">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        Link Google Drive (Hasil Foto)
                    </label>
                    <input type="url" name="result_link" value="{{ $booking->result_link }}" placeholder="https://drive.google.com/..." class="bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 block w-full px-4 py-3 transition-all outline-none placeholder-gray-400 shadow-sm mb-4">
                    
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Catatan untuk Pelanggan (Opsional)
                    </label>
                    <textarea name="result_notes" rows="3" placeholder="Contoh: Jangan lupa di download ya kak, link aktif 1 minggu..." class="bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 block w-full px-4 py-3 transition-all outline-none placeholder-gray-400 shadow-sm">{{ $booking->result_notes }}</textarea>
                    
                    <p class="text-[9px] text-gray-400 mt-1.5">*Kosongkan jika hasil foto belum siap. Jika diisi, link dan catatan akan muncul di dashboard pelanggan.</p>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeDetailModal({{ $booking->id }})" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-500 hover:text-brand-dark hover:bg-gray-50 text-xs font-extrabold rounded-xl transition flex items-center justify-center">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-brand-primary text-white hover:bg-blue-600 text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(43,84,136,0.3)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.4)] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Penugasan & Link
                    </button>
                </div>
            </form>
            
            <template id="employee-row-template-{{ $booking->id }}">
                <div class="flex items-center gap-2 employee-row">
                    <div class="relative flex-1">
                        <select name="employee_ids[]" onchange="updateEmployeeOptions('{{ $booking->id }}')" required class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-8">
                            <option value="">-- Pilih Fotografer --</option>
                            @foreach($employees as $employee)
                                @php
                                    $isOnLeave = false;
                                    if ($booking->booking_date) {
                                        $bDate = $booking->booking_date->format('Y-m-d');
                                        foreach($employee->leaveRequests as $leave) {
                                            if ($leave->status === 'approved' && $bDate >= $leave->start_date->format('Y-m-d') && $bDate <= $leave->end_date->format('Y-m-d')) {
                                                $isOnLeave = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                @if(!$isOnLeave)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="absolute bottom-3.5 right-3 flex items-center pointer-events-none text-brand-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </div>
                    <button type="button" onclick="removeEmployeeField(this, '{{ $booking->id }}')" class="btn-remove-emp w-10 h-10 shrink-0 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </template>
            
        </div>
    </div>
</div>
@endforeach

</div> <!-- End of schedules-data-container -->

@endsection
@push('scripts')
<script>
    function addEmployeeField(bookingId) {
        const container = document.getElementById('employee-container-' + bookingId);
        const template = document.getElementById('employee-row-template-' + bookingId);
        if (!container || !template) return;
        const clone = template.content.cloneNode(true);
        // Ensure the remove button calls removeEmployeeField with the bookingId
        const removeBtn = clone.querySelector('.btn-remove-emp');
        removeBtn.setAttribute('onclick', `removeEmployeeField(this, '${bookingId}')`);
        container.appendChild(clone);
        updateRemoveButtons(bookingId);
        updateEmployeeOptions(bookingId);
    }

    function removeEmployeeField(btn, bookingId) {
        const container = document.getElementById('employee-container-' + bookingId);
        if (container.querySelectorAll('.employee-row').length > 1) {
            btn.closest('.employee-row').remove();
        }
        updateRemoveButtons(bookingId);
        updateEmployeeOptions(bookingId);
    }

    function updateRemoveButtons(bookingId) {
        const container = document.getElementById('employee-container-' + bookingId);
        if (!container) return;
        const rows = container.querySelectorAll('.employee-row');
        rows.forEach(row => {
            const removeBtn = row.querySelector('.btn-remove-emp');
            if (removeBtn) {
                removeBtn.style.display = rows.length <= 1 ? 'none' : 'flex';
            }
        });
    }

    function updateEmployeeOptions(bookingId) {
        const container = document.getElementById('employee-container-' + bookingId);
        if (!container) return;
        
        const selects = Array.from(container.querySelectorAll('select[name="employee_ids[]"]'));
        const selectedValues = selects.map(s => s.value).filter(val => val !== '');

        selects.forEach(select => {
            const currentVal = select.value;
            Array.from(select.options).forEach(option => {
                if (option.value === '') return;
                if (selectedValues.includes(option.value) && option.value !== currentVal) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        });
    }

    function openBlokirModal() {
        const modal = document.getElementById('blokir-modal');
        const content = document.getElementById('blokir-modal-content');
        modal.classList.remove('hidden');
        void modal.offsetWidth; // trigger reflow
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'translate-y-4');
        content.classList.add('scale-100', 'translate-y-0');
    }

    function closeBlokirModal() {
        const modal = document.getElementById('blokir-modal');
        const content = document.getElementById('blokir-modal-content');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-4');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openDetailModal(id) {
        const modal = document.getElementById('detail-modal-' + id);
        const content = document.getElementById('detail-modal-content-' + id);
        if (modal && content) {
            modal.classList.remove('hidden');
            void modal.offsetWidth; // trigger reflow
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'translate-y-4');
            content.classList.add('scale-100', 'translate-y-0');
            
            // Perbarui ketersediaan opsi berdasarkan siapa yang sudah dipilih
            updateEmployeeOptions(id);
        }
    }

    function closeDetailModal(id) {
        const modal = document.getElementById('detail-modal-' + id);
        const content = document.getElementById('detail-modal-content-' + id);
        if (modal && content) {
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-4');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    function switchView(viewName) {
        // Elements
        const viewKalender = document.getElementById('view-kalender');
        const viewTimeline = document.getElementById('view-timeline');
        const viewList = document.getElementById('view-list');
        
        const btnKalender = document.getElementById('btn-kalender');
        const btnTimeline = document.getElementById('btn-timeline');
        const btnList = document.getElementById('btn-list');

        // Reset classes
        const inactiveClass = 'w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark hover:bg-gray-50 transition border-r border-gray-200';
        const inactiveClassNoBorder = 'w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark hover:bg-gray-50 transition';
        const activeClass = 'w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white border-r border-gray-200 transition';
        const activeClassNoBorder = 'w-28 justify-center py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white transition';

        btnKalender.className = inactiveClass;
        btnTimeline.className = inactiveClass;
        btnList.className = inactiveClassNoBorder;
        
        // Hide all views
        viewKalender.classList.add('hidden');
        viewTimeline.classList.add('hidden');
        viewList.classList.add('hidden');

        if(viewName === 'kalender') {
            viewKalender.classList.remove('hidden');
            btnKalender.className = activeClass;
        } else if(viewName === 'timeline') {
            viewTimeline.classList.remove('hidden');
            btnTimeline.className = activeClass;
        } else if(viewName === 'list') {
            viewList.classList.remove('hidden');
            btnList.className = activeClassNoBorder;
        }

        // Keep view in hidden input
        const viewInput = document.querySelector('input[name="view"]');
        if (viewInput) viewInput.value = viewName;

        // Keep view in URL without reloading
        const url = new URL(window.location.href);
        url.searchParams.set('view', viewName);
        window.history.replaceState({}, '', url);

        // Update all links on the page that contain 'view='
        document.querySelectorAll('a[href*="view="]').forEach(a => {
            try {
                const linkUrl = new URL(a.href);
                linkUrl.searchParams.set('view', viewName);
                a.href = linkUrl.toString();
            } catch(e) {}
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        switchView('{{ $view ?? 'list' }}');

        // Live AJAX Search & Filter
        const filterForm = document.getElementById('schedule-filter-form');
        const container = document.getElementById('schedules-data-container');
        let debounceTimer;

        window.filterByDate = function(date) {
            const filterDateInput = document.getElementById('filter-date');
            if (filterDateInput) {
                filterDateInput.value = date;
                switchView('list');
                fetchSchedules();
                
                // Scroll to top smoothly
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };

        function fetchSchedules() {
            const url = new URL(filterForm.action);
            const formData = new FormData(filterForm);
            
            for (const [key, value] of formData.entries()) {
                if(value) url.searchParams.set(key, value);
            }

            // Update URL bar
            window.history.pushState({}, '', url);

            container.style.opacity = '0.5';

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById('schedules-data-container');
                    
                    if (newContainer) {
                        container.innerHTML = newContainer.innerHTML;
                        // Re-initialize view script state since we replaced HTML
                        const viewInput = document.querySelector('input[name="view"]');
                        if (viewInput) switchView(viewInput.value);
                        // Re-initialize Alpine JS for the new elements
                        if (window.Alpine) {
                            window.Alpine.initTree(container);
                        }
                    }
                    container.style.opacity = '1';
                })
                .catch(() => {
                    container.style.opacity = '1';
                });
        }

        // Attach event listeners
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchSchedules();
        });

        // Trigger on form select changes
        filterForm.querySelectorAll('select').forEach(el => {
            el.addEventListener('change', fetchSchedules);
        });

        // Trigger on search input typing
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchSchedules();
                }, 400);
            });
        }

        // Intercept Pagination Links for AJAX
        container.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && link.href.includes('page=')) {
                e.preventDefault();
                
                // Set the current view explicitly in the pagination link before fetching
                const url = new URL(link.href);
                const viewInput = document.querySelector('input[name="view"]');
                if (viewInput) url.searchParams.set('view', viewInput.value);

                // Update URL bar
                window.history.pushState({}, '', url);

                container.style.opacity = '0.5';
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContainer = doc.getElementById('schedules-data-container');
                        
                        if (newContainer) {
                            container.innerHTML = newContainer.innerHTML;
                            // Re-apply view and re-update links
                            if (viewInput) switchView(viewInput.value);
                        }
                        container.style.opacity = '1';
                    })
                    .catch(() => container.style.opacity = '1');
            }
        });
    });
</script>
@endpush
