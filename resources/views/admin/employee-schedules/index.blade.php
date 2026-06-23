@extends('layouts.admin')

@section('title', 'Jadwal Pegawai')
@section('pre-title', 'Operasional')
@section('subtitle', 'Kelola penugasan dan jadwal kerja pegawai')

@section('content')

@if(session('success'))
<div class="mb-6 p-4 bg-green-50/80 border border-green-200/50 rounded-2xl flex items-center gap-4 animate-fade-in-up">
    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shrink-0 shadow-[0_4px_12px_rgba(34,197,94,0.3)]">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
    </div>
    <p class="text-green-800 font-bold text-sm">{{ session('success') }}</p>
</div>
@endif

<!-- 5 Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Bertugas hari ini</p>
        <h3 class="text-2xl font-black text-green-500 mb-1">{{ $stats['today_assignments'] }}</h3>
        <p class="text-[10px] font-bold text-green-500">Dari {{ $stats['total_employees'] }} pegawai aktif</p>
    </div>
    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Sedang sesi</p>
        <h3 class="text-2xl font-black text-blue-500 mb-1">{{ $stats['ongoing'] }}</h3>
        <p class="text-[10px] font-bold text-blue-500">Pegawai sedang bertugas</p>
    </div>
    <!-- Card 3 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Waktu jeda</p>
        <h3 class="text-2xl font-black text-yellow-500 mb-1">0</h3>
        <p class="text-[10px] font-bold text-yellow-500">Tidak ada</p>
    </div>
    <!-- Card 4 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Belum bertugas</p>
        <h3 class="text-2xl font-black text-gray-500 mb-1">{{ max(0, $stats['total_employees'] - $stats['ongoing']) }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Pegawai tersedia</p>
    </div>
    <!-- Card 5 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-center">
        <p class="text-[11px] font-bold text-gray-400 mb-1">Total sesi minggu ini</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1">{{ $stats['total_this_week'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">{{ $stats['total_employees'] }} pegawai</p>
    </div>
</div>

<!-- Controls Bar -->
<div class="flex flex-col xl:flex-row items-center justify-between gap-4 mb-4">
    <!-- Left Controls -->
    <div class="flex flex-wrap items-center gap-3">
        <!-- Date Navigator -->
        <div class="flex items-center bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:border-brand-primary/30 transition">
            <a href="{{ route('admin.employee-schedules.index', ['date' => $currentDate->copy()->subDay()->toDateString(), 'view' => $view]) }}" class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 hover:text-brand-primary transition border-r border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div class="relative group cursor-pointer flex items-center justify-center">
                <input type="date" 
                       id="date-picker-input"
                       value="{{ $currentDate->toDateString() }}" 
                       onchange="window.location.href='{{ route('admin.employee-schedules.index') }}?view={{ $view }}&date=' + this.value"
                       class="absolute w-0 h-0 opacity-0 overflow-hidden pointer-events-none" />
                <span id="date-text" 
                      onclick="document.getElementById('date-picker-input').showPicker()"
                      class="px-4 py-1.5 text-xs font-extrabold text-brand-dark bg-white group-hover:text-brand-primary transition block text-center min-w-[140px]">{{ $currentDate->translatedFormat('l, d F Y') }}</span>
            </div>

            <a href="{{ route('admin.employee-schedules.index', ['date' => $currentDate->copy()->addDay()->toDateString(), 'view' => $view]) }}" class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 hover:text-brand-primary transition border-l border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <a href="{{ route('admin.employee-schedules.index', ['view' => $view]) }}" class="px-4 py-1.5 bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg shadow-sm hover:bg-gray-50 transition">
            Hari ini
        </a>
        
        <div class="w-px h-6 bg-gray-300 mx-1"></div>

        <!-- Filters -->
        <div class="relative">
            <select onchange="window.location.href='{{ route('admin.employee-schedules.index') }}?view={{ $view }}&date={{ $currentDate->toDateString() }}&employee_id=' + this.value" class="appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-lg pl-4 pr-8 py-1.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                <option value="">Semua pegawai</option>
                @foreach($allEmployees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Right Controls -->
    <div class="flex items-center gap-3">
        <!-- Toggles -->
        <div class="flex items-center bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <button id="btn-harian" onclick="switchView('harian')" class="px-4 py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white border-r border-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Harian
            </button>
            <button id="btn-mingguan" onclick="switchView('mingguan')" class="px-4 py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 bg-gray-50 hover:text-brand-dark hover:bg-white transition border-r border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                Mingguan
            </button>
            <button id="btn-rekap" onclick="switchView('rekap')" class="px-4 py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 bg-gray-50 hover:text-brand-dark hover:bg-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Rekap
            </button>
        </div>
        
        <button onclick="openAssignModal()" class="px-4 py-1.5 bg-brand-dark text-white text-xs font-bold rounded-lg shadow-sm hover:bg-brand-primary transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Assign Tugas
        </button>
    </div>
</div>

<!-- Main Timeline Grid - Harian -->
<div id="view-harian" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    
    <!-- Grid Header Info -->
    <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xs font-extrabold text-brand-dark">Timeline Harian — <span id="header-date">{{ $currentDate->translatedFormat('l, d F Y') }}</span></h3>
            <p class="text-[10px] font-bold text-gray-400">Setiap baris = 1 pegawai · Setiap kolom = 30 menit</p>
        </div>
        
        <!-- Legend -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-blue-100 border-l-[3px] border-blue-400 rounded-sm"></div>
                <span class="text-[9px] font-bold text-gray-500">Sesi foto</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-yellow-50 border-l-[3px] border-yellow-400 border-dashed rounded-sm"></div>
                <span class="text-[9px] font-bold text-gray-500">Jeda otomatis</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-green-50 border-l-[3px] border-green-400 rounded-sm"></div>
                <span class="text-[9px] font-bold text-gray-500">Slot bebas</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-gray-50 border border-gray-200 rounded-sm"></div>
                <span class="text-[9px] font-bold text-gray-500">Tidak bertugas</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-red-50 border-l-[3px] border-red-400 rounded-sm"></div>
                <span class="text-[9px] font-bold text-gray-500">Sedang Libur</span>
            </div>
        </div>
    </div>

    <!-- The Grid Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left table-fixed min-w-[900px]">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="w-24 py-4 px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider border-r border-gray-100">Waktu</th>
                    
                    @php
                        $colors = ['bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500', 'bg-emerald-500', 'bg-blue-500', 'bg-indigo-500'];
                        $empCount = count($employees);
                        $colWidth = $empCount > 0 ? (100 / $empCount) . '%' : 'auto';
                    @endphp

                    @forelse($employees as $index => $emp)
                    <th class="py-3 px-4 border-r border-gray-100" style="width: {{ $colWidth }}">
                        <div class="flex items-center gap-2">
                            @if($emp->avatar)
                                <img src="{{ asset('images/profile_akun/' . strtolower($emp->role) . '/' . $emp->avatar) }}" alt="{{ $emp->name }}" class="w-8 h-8 rounded-lg object-cover border border-gray-100 shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-lg {{ $colors[$index % count($colors)] }} text-white flex items-center justify-center text-xs font-black shrink-0">
                                    {{ strtoupper(substr($emp->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="text-[11px] font-extrabold text-brand-dark leading-tight">{{ $emp->name }}</h4>
                                <p class="text-[9px] text-gray-400 font-bold">{{ $emp->role == 'admin' ? 'Admin' : 'Fotografer' }}</p>
                            </div>
                        </div>
                    </th>
                    @empty
                    <th class="py-3 px-4 text-center text-gray-400 text-xs">Belum ada pegawai.</th>
                    @endforelse
                </tr>
            </thead>
            <tbody class="text-xs">
                @foreach($timeSlots as $time)
                <tr class="border-b border-gray-100">
                    <td class="py-2 px-4 text-[10px] font-bold text-gray-400 border-r border-gray-100">{{ str_replace(':', '.', $time) }}</td>
                    
                    @foreach($employees as $emp)
                        @php
                            $slotData = $timeline[$time][$emp->id] ?? ['status' => 'free', 'assignment' => null];
                        @endphp
                        
                        <td class="p-1.5 border-r border-gray-100">
                            @if($slotData['status'] == 'booked')
                                <div class="h-10 bg-blue-50 border-l-[3px] border-blue-500 rounded px-2 py-0.5 flex flex-col justify-center cursor-pointer hover:bg-blue-100 transition"
                                     onclick="window.location.href='{{ route('admin.schedules.index') }}'">
                                    <p class="text-[9px] font-extrabold text-brand-dark leading-tight truncate">{{ $slotData['assignment']->booking->user->name ?? 'Klien' }}</p>
                                    <p class="text-[8px] font-bold text-blue-500 truncate">{{ $slotData['assignment']->booking->package->name ?? 'Paket' }}</p>
                                </div>
                            @elseif($slotData['status'] == 'leave')
                                <div class="h-10 bg-red-50 border-l-[3px] border-red-400 rounded px-2 py-1 flex items-center justify-center">
                                    <p class="text-[9px] font-extrabold text-red-600">Sedang Libur</p>
                                </div>
                            @else
                                <div class="h-10 bg-green-50 border-l-[3px] border-green-400 rounded px-2 py-1 flex items-center">
                                    <p class="text-[9px] font-extrabold text-green-600">Bebas</p>
                                </div>
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Main View - Mingguan -->
<div id="view-mingguan" class="hidden">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xs font-extrabold text-brand-dark">Timeline Mingguan — <span id="header-week-date">{{ $currentDate->copy()->startOfWeek()->translatedFormat('d M') }} - {{ $currentDate->copy()->endOfWeek()->translatedFormat('d M Y') }}</span></h3>
                <p class="text-[10px] font-bold text-gray-400">Total sesi foto per pegawai setiap harinya</p>
            </div>
            <!-- Legend -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 bg-blue-100 border border-blue-200 rounded-sm"></div>
                    <span class="text-[9px] font-bold text-gray-500">Ada Sesi</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 bg-gray-50 rounded-sm"></div>
                    <span class="text-[9px] font-bold text-gray-500">Jadwal Kosong</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left table-fixed min-w-[900px]">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/30">
                        <th class="w-48 py-3 px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider border-r border-gray-100">Pegawai</th>
                        @php
                            $weekStart = $currentDate->copy()->startOfWeek();
                            $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                        @endphp
                        @foreach($days as $index => $day)
                        @php 
                            $dateCol = $weekStart->copy()->addDays($index); 
                            $isActive = $dateCol->isSameDay($currentDate);
                        @endphp
                        <th class="py-3 px-2 border-r border-gray-100 text-center align-middle {{ $isActive ? 'bg-slate-50' : '' }}">
                            <div class="flex flex-col items-center gap-1.5">
                                <span class="text-[10px] {{ $isActive ? 'font-black text-brand-dark' : 'font-extrabold text-brand-dark' }}">{{ $day }}</span>
                                <div class="w-6 h-6 flex items-center justify-center rounded-full text-[11px] font-black {{ $isActive ? 'bg-brand-dark text-white shadow-md' : 'text-gray-500' }}">
                                    {{ $dateCol->format('d') }}
                                </div>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse($employees as $index => $emp)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/30 transition">
                        <td class="py-3 px-4 border-r border-gray-100">
                            <div class="flex items-center gap-2">
                                @if($emp->avatar)
                                    <img src="{{ asset('images/profile_akun/' . strtolower($emp->role) . '/' . $emp->avatar) }}" alt="{{ $emp->name }}" class="w-8 h-8 rounded-lg object-cover border border-gray-100 shrink-0">
                                @else
                                    @php $colors = ['bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500', 'bg-emerald-500', 'bg-blue-500', 'bg-indigo-500']; @endphp
                                    <div class="w-8 h-8 rounded-lg {{ $colors[$index % count($colors)] }} text-white flex items-center justify-center text-xs font-black shrink-0">
                                        {{ strtoupper(substr($emp->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-[11px] font-extrabold text-brand-dark leading-tight">{{ $emp->name }}</h4>
                                    <p class="text-[9px] text-gray-400 font-bold">{{ $emp->role == 'admin' ? 'Admin' : 'Fotografer' }}</p>
                                </div>
                            </div>
                        </td>
                        @foreach($days as $dayIndex => $day)
                            @php 
                                $cellDate = $weekStart->copy()->addDays($dayIndex);
                                $isActive = $cellDate->isSameDay($currentDate);
                                $dailyAssignments = $emp->assignments()->whereHas('booking', function($q) use ($cellDate) {
                                    $q->whereDate('booking_date', $cellDate);
                                })->with('booking')->get()->sortBy('booking.start_time');
                            @endphp
                            <td class="p-2 border-r border-gray-100 text-center align-top relative group {{ $isActive ? 'bg-slate-50/30' : '' }}">
                                @if($dailyAssignments->count() > 0)
                                    <div class="flex flex-col gap-1.5 w-full h-full cursor-pointer" onclick="window.location.href='{{ route('admin.employee-schedules.index') }}?view=harian&date={{ $cellDate->toDateString() }}'">
                                        @foreach($dailyAssignments as $da)
                                            @php
                                                $start = \Carbon\Carbon::parse($da->booking->start_time)->format('H:i');
                                                $end = \Carbon\Carbon::parse($da->booking->end_time)->format('H:i');
                                            @endphp
                                            <div class="flex items-center justify-center w-full min-h-[40px] bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 hover:border-blue-200 transition px-1">
                                                <span class="text-[10px] font-black text-blue-600 leading-none">{{ $start }} - {{ $end }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex items-center justify-center w-full h-full min-h-[40px]">
                                        <span class="text-[12px] font-bold text-gray-300">-</span>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400 text-sm">Belum ada pegawai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Main View - Rekap -->
<div id="view-rekap" class="hidden">
    
    <!-- Table Rekap -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-200">
            <h3 class="text-sm font-extrabold text-brand-dark">Rekap Kinerja Pegawai</h3>
            <p class="text-[11px] font-bold text-gray-400">{{ $currentDate->translatedFormat('F Y') }} - {{ $stats['total_employees'] }} pegawai aktif</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/30 text-gray-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-3 px-5">Pegawai</th>
                        <th class="py-3 px-5 text-center">Sesi Bulan Ini</th>
                        <th class="py-3 px-5 text-center">Jam Kerja</th>
                        <th class="py-3 px-5 text-center">Rata-Rata/Hari</th>
                        <th class="py-3 px-5 text-center">Paket Terbanyak</th>
                        <th class="py-3 px-5">Beban Kerja</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-bold text-gray-600">
                    
                    @forelse($employees as $employee)
                    @php
                        $colors = ['purple', 'pink', 'teal', 'orange', 'emerald', 'blue'];
                        $color = $colors[array_rand($colors)];
                        $initials = collect(explode(' ', $employee->name))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                        $sessionsCount = $employee->assignments->count();
                        $hoursCount = $employee->assignments->sum(function($assignment) {
                            if (!$assignment->booking) return 0;
                            $start = \Carbon\Carbon::parse($assignment->booking->start_time);
                            $end = \Carbon\Carbon::parse($assignment->booking->end_time);
                            return $start->diffInMinutes($end) / 60;
                        });
                        $load = min(100, ($hoursCount / 40) * 100); // Assuming 40 hours is max load
                        
                        $packages = $employee->assignments->map(function($assignment) {
                            return $assignment->booking->package->name ?? null;
                        })->filter()->countBy()->sortDesc();
                        
                        $topPackage = $packages->keys()->first() ?? '-';
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-3">
                                @if($employee->avatar)
                                    <img src="{{ asset('images/profile_akun/' . strtolower($employee->role) . '/' . $employee->avatar) }}" alt="{{ $employee->name }}" class="w-8 h-8 rounded-lg object-cover border border-gray-100 shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-{{$color}}-500 text-white flex items-center justify-center text-xs font-black shrink-0">{{ strtoupper($initials) }}</div>
                                @endif
                                <div>
                                    <h4 class="text-[11px] font-extrabold text-brand-dark leading-tight">{{ $employee->name }}</h4>
                                    <p class="text-[9px] text-gray-400">{{ ['pegawai' => 'Pegawai', 'admin' => 'Admin', 'user' => 'Pelanggan'][$employee->role] ?? ucfirst($employee->role) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-5 text-center font-black text-brand-dark text-sm">{{ $sessionsCount }}</td>
                        <td class="py-3 px-5 text-center text-gray-500">{{ $hoursCount }} jam</td>
                        <td class="py-3 px-5 text-center text-gray-500">{{ number_format($sessionsCount / max(1, now()->day), 1) }} sesi</td>
                        <td class="py-3 px-5 text-center">
                            <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-600 text-[9px] font-extrabold rounded-full">{{ $topPackage }}</span>
                        </td>
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="w-[{{ $load }}%] h-full {{ $load > 70 ? 'bg-red-500' : ($load > 40 ? 'bg-yellow-400' : 'bg-green-500') }} rounded-full"></div>
                                </div>
                                <span class="text-[10px] text-gray-500 font-extrabold w-8">{{ $load }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 text-sm">Belum ada pegawai.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Bar Chart Left: Distribusi Sesi per Pegawai -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-extrabold text-brand-dark">Distribusi Sesi per Pegawai</h3>
            <p class="text-[10px] font-bold text-gray-400 mb-6">{{ $currentDate->translatedFormat('F Y') }}</p>
            
            <div class="flex flex-col gap-4">
                @php
                    $colors = ['purple', 'pink', 'teal', 'orange', 'emerald', 'blue', 'indigo'];
                    $monthStart = $currentDate->copy()->startOfMonth();
                    $monthEnd = $currentDate->copy()->endOfMonth();
                    $maxSessions = 1;
                    $employeeStats = [];
                    foreach($employees as $emp) {
                        $count = $emp->assignments()->whereHas('booking', function($q) use ($monthStart, $monthEnd) {
                            $q->whereBetween('booking_date', [$monthStart, $monthEnd]);
                        })->count();
                        if ($count > $maxSessions) $maxSessions = $count;
                        $employeeStats[] = ['emp' => $emp, 'count' => $count];
                    }
                    usort($employeeStats, function($a, $b) { return $b['count'] <=> $a['count']; });
                @endphp
                
                @forelse(array_slice($employeeStats, 0, 5) as $index => $stat)
                    @php
                        $percentage = max(5, ($stat['count'] / $maxSessions) * 100);
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="flex items-center gap-4">
                        <span class="text-[11px] font-bold text-gray-500 w-24 shrink-0 truncate">{{ $stat['emp']->name }}</span>
                        <div class="w-full h-5 bg-gray-100 rounded-md overflow-hidden relative">
                            <div class="h-full bg-{{ $color }}-500 rounded-md flex items-center px-2 transition-all duration-1000" style="width: {{ $percentage }}%">
                                <span class="text-[9px] font-black text-white">{{ $stat['count'] }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-[11px] font-bold text-gray-400 text-center py-4">Belum ada sesi di bulan ini</p>
                @endforelse
            </div>
        </div>

        <!-- Vertical Bar Chart Right: Sesi per Hari -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-extrabold text-brand-dark">Sesi per Hari (Minggu ini)</h3>
            <p class="text-[10px] font-bold text-gray-400 mb-6">{{ $currentDate->copy()->startOfWeek()->translatedFormat('d') }} - {{ $currentDate->copy()->endOfWeek()->translatedFormat('d F Y') }}</p>
            
            <div class="h-40 flex items-end justify-between px-2 gap-2 border-b border-gray-100 pb-2">
                @php
                    $weekStart = $currentDate->copy()->startOfWeek();
                    $dailyCounts = [];
                    $maxDaily = 1;
                    for($i = 0; $i < 7; $i++) {
                        $date = $weekStart->copy()->addDays($i);
                        $count = \App\Models\Assignment::whereHas('booking', function($q) use ($date) {
                            $q->whereDate('booking_date', $date);
                        })->count();
                        $dailyCounts[] = $count;
                        if($count > $maxDaily) $maxDaily = $count;
                    }
                @endphp
                @foreach($dailyCounts as $count)
                <div class="flex flex-col items-center justify-end gap-1 w-full group h-full cursor-pointer">
                    <span class="text-[10px] font-black text-brand-primary opacity-0 group-hover:opacity-100 transition">{{ $count > 0 ? $count : '0' }}</span>
                    <div class="w-full h-[110px] bg-gray-100 rounded-t-md relative flex items-end overflow-hidden">
                        @if($count > 0)
                            <div class="w-full bg-brand-dark rounded-t-md hover:bg-brand-primary transition-colors duration-300" style="height: {{ max(10, ($count / $maxDaily) * 100) }}%"></div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Labels -->
            <div class="flex justify-between px-2 pt-2 text-[10px] font-bold text-gray-400 text-center">
                @php
                    $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                @endphp
                @foreach($days as $index => $day)
                    <span class="w-full {{ $weekStart->copy()->addDays($index)->isToday() ? 'text-brand-dark font-extrabold' : '' }}">{{ $day }}</span>
                @endforeach
            </div>
        </div>

    </div>
</div>

<!-- Status Real-time Pegawai -->
<div id="status-realtime" class="mb-8">
    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Status Real-Time Pegawai</h3>
    <div class="flex flex-wrap items-center gap-3">
        @forelse($employees as $employee)
            @php
                $isOngoing = false;
                $nowStr = \Carbon\Carbon::now()->format('H:i');
                foreach($timeSlots as $time) {
                    if(isset($timeline[$time][$employee->id]) && $timeline[$time][$employee->id]['status'] == 'booked') {
                        $assignment = $timeline[$time][$employee->id]['assignment'];
                        if($assignment && $assignment->booking) {
                            $start = \Carbon\Carbon::parse($assignment->booking->start_time)->format('H:i');
                            $end = \Carbon\Carbon::parse($assignment->booking->end_time)->format('H:i');
                            if($nowStr >= $start && $nowStr < $end) {
                                $isOngoing = true;
                                break;
                            }
                        }
                    }
                }
                $initials = strtoupper(substr($employee->name, 0, 2));
                $isOnLeave = $employee->leaveRequests->isNotEmpty();
                $bgColor = $isOnLeave ? 'bg-red-400' : ($isOngoing ? 'bg-purple-500' : 'bg-gray-400');
            @endphp
            <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl py-2 pl-2 pr-4 shadow-sm">
                @if($employee->avatar)
                    <div class="w-8 h-8 rounded-lg overflow-hidden border-2 {{ $isOnLeave ? 'border-red-400' : ($isOngoing ? 'border-purple-500' : 'border-gray-100') }} shrink-0">
                        <img src="{{ asset('images/profile_akun/' . strtolower($employee->role) . '/' . $employee->avatar) }}" alt="{{ $employee->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-8 h-8 rounded-lg {{ $bgColor }} text-white flex items-center justify-center text-xs font-black shrink-0">{{ $initials }}</div>
                @endif
                <div>
                    <p class="text-xs font-extrabold text-brand-dark leading-none mb-1">{{ $employee->name }}</p>
                    <p class="text-[9px] font-bold text-gray-500 flex items-center gap-1">
                        @if($isOnLeave)
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            <span class="text-red-500">Sedang Libur</span>
                        @elseif($isOngoing)
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Sedang sesi
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                            Bebas
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <p class="text-xs text-gray-400 font-bold">Belum ada pegawai.</p>
        @endforelse
    </div>
</div>

<!-- Modal Assign Tugas -->
<div id="assign-modal" class="fixed inset-0 z-[60] flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeAssignModal()"></div>
    <div id="assign-modal-content" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[450px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col">
        <form id="form-assign" method="POST" onsubmit="return submitAssignForm()">
            @csrf
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-black text-brand-dark tracking-tight">Assign Tugas Baru</h3>
                <button type="button" onclick="closeAssignModal()" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 flex flex-col gap-5">
                <!-- Pilih Sesi -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Sesi Booking (Belum diassign)</label>
                    <div class="relative">
                        <select id="assign_booking_id" name="booking_id" required class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl pl-4 pr-10 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                            <option value="" disabled selected>Pilih sesi booking...</option>
                            @foreach($unassignedBookings as $booking)
                                <option value="{{ $booking->id }}">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->start_time)->format('H.i') }} - {{ $booking->user->name ?? 'Guest' }} ({{ $booking->package->name ?? 'Kustom' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Pilih Pegawai -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-2">Pegawai Bertugas</label>
                    <div class="bg-white border border-gray-200 rounded-xl max-h-48 overflow-y-auto p-2 shadow-sm">
                        <div class="flex flex-col gap-1">
                            @foreach($allEmployees as $employee)
                            <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="w-4 h-4 text-brand-primary bg-white border-gray-300 rounded focus:ring-brand-primary focus:ring-2">
                                <div class="flex items-center gap-2">
                                    @if($employee->avatar)
                                        <img src="{{ asset('images/profile_akun/' . strtolower($employee->role) . '/' . $employee->avatar) }}" alt="{{ $employee->name }}" class="w-6 h-6 rounded-md object-cover border border-gray-100">
                                    @else
                                        @php $colors = ['bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500', 'bg-emerald-500', 'bg-blue-500', 'bg-indigo-500']; @endphp
                                        <div class="w-6 h-6 rounded-md {{ $colors[$loop->index % count($colors)] }} text-white flex items-center justify-center text-[10px] font-black shrink-0">
                                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="text-xs font-bold text-brand-dark">{{ $employee->name }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Catatan Khusus BK (Opsional)</label>
                    <textarea name="result_notes" rows="3" placeholder="Contoh: Link drive, atau catatan terkait booking ini..." class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300 resize-none"></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-end gap-3 shrink-0 bg-gray-50/50 rounded-b-[24px]">
                <button type="button" onclick="closeAssignModal()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-extrabold rounded-xl transition flex items-center justify-center">
                    Batal
                </button>
                <button type="submit" class="px-8 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Assign
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchView(view) {
        // Elements
        const viewHarian = document.getElementById('view-harian');
        const viewMingguan = document.getElementById('view-mingguan');
        const viewRekap = document.getElementById('view-rekap');
        const statusRealtime = document.getElementById('status-realtime');
        
        const btnHarian = document.getElementById('btn-harian');
        const btnMingguan = document.getElementById('btn-mingguan');
        const btnRekap = document.getElementById('btn-rekap');
        
        const dateText = document.getElementById('date-text');

        // Reset Buttons
        [btnHarian, btnMingguan, btnRekap].forEach(btn => {
            btn.className = 'px-4 py-1.5 flex items-center gap-2 text-xs font-bold text-gray-400 bg-gray-50 hover:text-brand-dark hover:bg-white transition border-r border-gray-200';
        });
        // Remove right border from last button
        btnRekap.classList.remove('border-r', 'border-gray-200');

        // Hide all views
        viewHarian.classList.add('hidden');
        viewMingguan.classList.add('hidden');
        viewRekap.classList.add('hidden');
        statusRealtime.classList.add('hidden');

        if (view === 'harian') {
            viewHarian.classList.remove('hidden');
            statusRealtime.classList.remove('hidden');
            btnHarian.className = 'px-4 py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white border-r border-gray-200 transition';
            dateText.innerText = '{{ $currentDate->translatedFormat("l, d F Y") }}';
        } else if (view === 'mingguan') {
            viewMingguan.classList.remove('hidden');
            btnMingguan.className = 'px-4 py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white border-r border-gray-200 transition';
            dateText.innerText = '{{ $currentDate->copy()->startOfWeek()->translatedFormat("d") }} - {{ $currentDate->copy()->endOfWeek()->translatedFormat("d F Y") }}';
        } else if (view === 'rekap') {
            viewRekap.classList.remove('hidden');
            btnRekap.className = 'px-4 py-1.5 flex items-center gap-2 text-xs font-extrabold text-brand-dark bg-white transition';
            dateText.innerText = '{{ $currentDate->translatedFormat("F Y") }}';
        }
    }

    function openAssignModal() {
        const modal = document.getElementById('assign-modal');
        const content = document.getElementById('assign-modal-content');
        modal.classList.remove('hidden');
        void modal.offsetWidth; // trigger reflow
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'translate-y-4');
        content.classList.add('scale-100', 'translate-y-0');
    }

    function closeAssignModal() {
        const modal = document.getElementById('assign-modal');
        const content = document.getElementById('assign-modal-content');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-4');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function submitAssignForm() {
        const select = document.getElementById('assign_booking_id');
        const bookingId = select.value;
        if(!bookingId) {
            alert('Pilih sesi booking terlebih dahulu!');
            return false;
        }
        
        const checkboxes = document.querySelectorAll('input[name="employee_ids[]"]:checked');
        if(checkboxes.length === 0) {
            alert('Pilih minimal 1 pegawai bertugas!');
            return false;
        }
        
        const form = document.getElementById('form-assign');
        form.action = `/admin/schedules/${bookingId}/assign`;
        return true;
    }
</script>
@endpush

@endsection
