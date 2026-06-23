@extends('employee.layouts.app')

@section('content')
<div x-data="{ submitModalOpen: false, bookingId: null, clientName: '', packageInfo: '' }">
<!-- Hero Card -->
<div class="bg-brand-primary rounded-[24px] p-8 mb-6 shadow-md text-white relative overflow-hidden">
    <div class="relative z-10">
        <p class="text-blue-200 text-[11px] mb-1 font-medium tracking-wide">Hari ini</p>
        <h2 class="text-[28px] font-bold mb-2 tracking-tight">Kamu punya <span class="text-yellow-400">{{ $assignmentsToday->count() }} sesi</span> hari ini</h2>
        @if($assignmentsToday->count() > 0)
            <p class="text-blue-100 text-sm font-medium">Sesi pertama mulai pukul {{ \Carbon\Carbon::parse($assignmentsToday->first()->booking->start_time)->format('H.i') }} &middot; {{ $assignmentsToday->first()->booking->package->name ?? 'Paket' }} &middot; Klien: {{ $assignmentsToday->first()->booking->user->name ?? 'Anonim' }}</p>
        @else
            <p class="text-blue-100 text-sm font-medium">Tidak ada jadwal sesi pemotretan untuk hari ini.</p>
        @endif
    </div>
    <!-- Decor element -->
    <div class="absolute right-10 top-1/2 -translate-y-1/2 bg-white/10 p-5 rounded-2xl backdrop-blur-sm border border-white/20">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between transition-colors">
        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-2">Sesi hari ini</p>
        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-1">{{ $assignmentsToday->count() }}</h3>
        <p class="text-gray-400 dark:text-gray-500 text-xs">{{ $todayTimeRange }}</p>
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between transition-colors">
        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-2">Hasil belum dikirim</p>
        <h3 class="text-3xl font-bold text-orange-500 dark:text-orange-400 mb-1">{{ $pendingResults->count() }}</h3>
        @if($pendingResults->count() > 0)
            <p class="text-yellow-600 dark:text-yellow-500 text-xs font-medium">Perlu segera dikirim</p>
        @else
            <p class="text-green-500 dark:text-green-400 text-xs font-medium">Semua aman</p>
        @endif
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between transition-colors">
        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-2">Sesi bulan ini</p>
        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-1">{{ $assignmentsMonth }}</h3>
        @if($monthDiff > 0)
            <p class="text-green-500 dark:text-green-400 text-xs font-medium">+{{ $monthDiff }} vs bulan lalu</p>
        @elseif($monthDiff < 0)
            <p class="text-red-500 dark:text-red-400 text-xs font-medium">{{ $monthDiff }} vs bulan lalu</p>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">Sama dengan bulan lalu</p>
        @endif
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between transition-colors">
        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-2">Total sesi all-time</p>
        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-1">{{ $totalAssignments }}</h3>
        <p class="text-gray-400 dark:text-gray-500 text-xs">Sejak {{ $joinedDate }}</p>
    </div>
</div>

<!-- Main Grid Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Timeline Left Column -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col transition-colors">
        <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-brand-dark dark:text-white">Timeline Hari Ini</h3>
                <p class="text-gray-400 dark:text-gray-500 text-[11px] font-medium">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('employee.jadwal') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-700 text-brand-dark dark:text-white text-xs font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">Lihat semua</a>
        </div>
        
        <div class="p-6 flex-1 flex flex-col gap-3 bg-gray-50/30 dark:bg-slate-800/50 transition-colors">
            @if($assignmentsToday->isEmpty())
                <div class="text-center py-10 text-gray-500">
                    Tidak ada jadwal untuk hari ini.
                </div>
            @else
                @php
                    $lastEndTime = null;
                @endphp
                
                @foreach($assignmentsToday as $index => $assignment)
                    @php
                        $startTime = \Carbon\Carbon::parse($assignment->booking->start_time);
                        $endTime = \Carbon\Carbon::parse($assignment->booking->end_time);
                        $startFmt = $startTime->format('H.i');
                        $endFmt = $endTime->format('H.i');
                        $clientName = $assignment->booking->user->name ?? 'Klien';
                        $packageName = $assignment->booking->package->name ?? 'Paket';
                        $status = $assignment->booking->status;
                        
                        $isPast = $endTime < now();
                        $isNow = $startTime <= now() && $endTime >= now();
                        
                        // Check for jeda before this session
                        if ($lastEndTime) {
                            $gapMinutes = $lastEndTime->diffInMinutes($startTime);
                            if ($gapMinutes > 0) {
                                // Only show jeda up to jedaDuration + 15
                                if ($gapMinutes <= $jedaDuration + 15) {
                                    $jedaStartFmt = $lastEndTime->format('H.i');
                                    $jedaEndFmt = $startTime->format('H.i');
                                    $isJedaPast = $startTime < now();
                                    $isJedaNow = $lastEndTime <= now() && $startTime >= now();
                                    
                                    echo '<div class="flex items-stretch w-full relative">
                                            <div class="w-1.5 bg-yellow-400 rounded-l-md relative">';
                                            
                                    if ($isJedaNow) {
                                        echo '<div class="absolute top-4 -left-1 w-3.5 h-3.5 bg-yellow-500 rounded-full animate-ping opacity-75"></div>
                                              <div class="absolute top-4 -left-1 w-3.5 h-3.5 bg-yellow-600 rounded-full border-2 border-white dark:border-slate-800"></div>';
                                    }
                                            
                                    echo '</div>
                                            <div class="flex-1 bg-yellow-50 dark:bg-yellow-900/20 px-4 sm:px-6 py-4 rounded-r-md flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-3 border border-yellow-100 dark:border-yellow-800/50 border-l-0 transition-colors relative">
                                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-6 w-full sm:w-auto">
                                                    <span class="text-yellow-600/70 dark:text-yellow-500/70 font-medium text-sm sm:w-24">'.$jedaStartFmt.' - '.$jedaEndFmt.'</span>
                                                    <div class="flex items-center gap-2 text-yellow-800 dark:text-yellow-400 font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Jeda otomatis '.$gapMinutes.' mnt
                                                    </div>
                                                </div>';
                                                
                                    if ($isJedaPast) {
                                        echo '<span class="text-gray-500 dark:text-gray-400 font-bold text-[10px] bg-gray-100 dark:bg-slate-700/50 px-3 py-1 rounded-md transition-colors w-max">Selesai</span>';
                                    } elseif ($isJedaNow) {
                                        echo '<span class="text-yellow-600 dark:text-yellow-400 font-bold text-[10px] bg-yellow-100 dark:bg-yellow-900/50 px-3 py-1 rounded-md transition-colors w-max">Berlangsung</span>';
                                    }
                                                
                                    echo '</div>
                                          </div>';
                                }
                            }
                        }
                    @endphp
                    
                    <!-- Session Box -->
                    <div class="flex items-stretch w-full relative">
                        <div class="w-1.5 bg-blue-500 rounded-l-md relative">
                            @if($status === 'in_progress')
                                <div class="absolute top-4 -left-1 w-3.5 h-3.5 bg-blue-500 rounded-full animate-ping opacity-75"></div>
                                <div class="absolute top-4 -left-1 w-3.5 h-3.5 bg-blue-600 rounded-full border-2 border-white dark:border-slate-800"></div>
                            @endif
                        </div>
                        <div class="flex-1 bg-brand-light dark:bg-slate-800/80 px-4 sm:px-6 py-4 rounded-r-md flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-3 border border-blue-100 dark:border-slate-700/50 border-l-0 transition-colors">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-6 w-full sm:w-auto">
                                <span class="text-blue-500 font-bold text-sm sm:w-24">{{ $startFmt }} - {{ $endFmt }}</span>
                                <span class="text-brand-dark dark:text-white font-bold text-sm">{{ $clientName }} . - {{ $packageName }}</span>
                            </div>
                            @if($status === 'completed')
                                <span class="text-gray-500 dark:text-gray-400 font-bold text-[10px] bg-gray-100 dark:bg-slate-700/50 px-3 py-1 rounded-md transition-colors w-max">Selesai</span>
                            @elseif($status === 'in_progress')
                                <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400 font-bold text-[10px] bg-blue-100 dark:bg-blue-900/50 px-3 py-1 rounded-md transition-colors w-max">Berlangsung</span>
                            @else
                                <span class="text-blue-600 dark:text-blue-400 font-bold text-[10px] bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-md transition-colors w-max">Menunggu</span>
                            @endif
                        </div>
                    </div>
                    
                    @php
                        $lastEndTime = clone $endTime;
                    @endphp
                @endforeach
                
                @php
                    $finalJedaEnd = (clone $lastEndTime)->addMinutes($jedaDuration);
                    $isFinalJedaPast = $finalJedaEnd < now();
                    $isFinalJedaNow = $lastEndTime <= now() && $finalJedaEnd >= now();
                @endphp
                
                <!-- Final Jeda if needed -->
                <div class="flex items-stretch w-full relative">
                    <div class="w-1.5 bg-yellow-400 rounded-l-md relative">
                        @if($isFinalJedaNow)
                            <div class="absolute top-4 -left-1 w-3.5 h-3.5 bg-yellow-500 rounded-full animate-ping opacity-75"></div>
                            <div class="absolute top-4 -left-1 w-3.5 h-3.5 bg-yellow-600 rounded-full border-2 border-white dark:border-slate-800"></div>
                        @endif
                    </div>
                    <div class="flex-1 bg-yellow-50 dark:bg-yellow-900/20 px-4 sm:px-6 py-4 rounded-r-md flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-3 border border-yellow-100 dark:border-yellow-800/50 border-l-0 transition-colors">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-6 w-full sm:w-auto">
                            <span class="text-yellow-600/70 dark:text-yellow-500/70 font-medium text-sm sm:w-24">{{ $lastEndTime->format('H.i') }} - {{ $finalJedaEnd->format('H.i') }}</span>
                            <div class="flex items-center gap-2 text-yellow-800 dark:text-yellow-400 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Jeda otomatis {{ $jedaDuration }} mnt
                            </div>
                        </div>
                        @if($isFinalJedaPast)
                            <span class="text-gray-500 dark:text-gray-400 font-bold text-[10px] bg-gray-100 dark:bg-slate-700/50 px-3 py-1 rounded-md transition-colors w-max">Selesai</span>
                        @elseif($isFinalJedaNow)
                            <span class="text-yellow-600 dark:text-yellow-400 font-bold text-[10px] bg-yellow-100 dark:bg-yellow-900/50 px-3 py-1 rounded-md transition-colors w-max">Berlangsung</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="flex flex-col gap-6">
        
        <!-- Hasil belum dikirim Box -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-yellow-200 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="p-4 border-b border-yellow-100 dark:border-slate-700 flex items-center gap-2 bg-yellow-50/50 dark:bg-slate-800/50 transition-colors">
                <svg class="w-5 h-5 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="text-sm font-bold text-orange-700 dark:text-orange-400">Hasil belum dikirim</h3>
            </div>
            
            <div class="p-4 flex flex-col gap-4">
                @forelse($pendingResults as $pending)
                    <div class="border border-yellow-200 dark:border-slate-600 rounded-lg p-4 bg-white dark:bg-slate-700/50 relative transition-colors">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $pending->booking->user->name ?? 'Klien' }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $pending->booking->package->name ?? 'Paket' }} &middot; {{ \Carbon\Carbon::parse($pending->booking->booking_date)->format('d M') }} &middot; #IMK-{{ $pending->booking->booking_code }}</p>
                        
                        <button @click="submitModalOpen = true; bookingId = '{{ $pending->booking->id }}'; clientName = '{{ $pending->booking->user->name ?? 'Klien' }}'; packageInfo = '{{ $pending->booking->package->name ?? 'Paket' }}'" class="px-5 py-2.5 bg-brand-dark text-white text-[11px] font-bold rounded-lg hover:bg-brand-primary transition-colors flex items-center gap-2 w-max">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Kirim sekarang
                        </button>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Semua hasil sudah terkirim.</p>
                @endforelse
            </div>
        </div>
        
        <!-- Aktivitas Terbaru -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex-1 transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-slate-700 transition-colors">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100">Aktivitas Terbaru</h3>
            </div>
            
            <div class="p-5 flex flex-col gap-6">
                @forelse($activityLogs as $log)
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-6 h-6 rounded bg-{{ $log->icon_color }}-100 dark:bg-{{ $log->icon_color }}-900/50 text-{{ $log->icon_color }}-600 dark:text-{{ $log->icon_color }}-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $log->icon_svg !!}</svg>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300">{{ $log->title }}</span>
                    </div>
                    <span class="text-gray-400 dark:text-gray-500 text-xs">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                    Belum ada aktivitas.
                </div>
                @endforelse
            </div>
        </div>
        
    </div>
    
    <!-- Modal Kirim Hasil -->
    <div x-show="submitModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" @click="submitModalOpen = false"></div>
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-md p-6 relative z-10 mx-4 transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Kirim Hasil Foto</h3>
                <button @click="submitModalOpen = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="mb-5 p-4 bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-xl">
                <p class="text-sm text-gray-600 dark:text-gray-300">Sesi untuk klien <span x-text="clientName" class="font-bold text-gray-800 dark:text-gray-100"></span> (<span x-text="packageInfo" class="text-indigo-600 dark:text-indigo-400 font-medium"></span>).</p>
            </div>
            
            <!-- Note: Form action needs to be dynamic. Since we can't fully execute PHP in Alpine, we'll use a hidden input for booking_id or manipulate the action string via Alpine JS. For better security and ease, we'll dynamically set the form action in Alpine. -->
            <form :action="'{{ url('pegawai/booking') }}/' + bookingId + '/result'" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tautan (Link) Google Drive</label>
                    <input type="url" name="result_link" placeholder="https://drive.google.com/..." required class="w-full text-sm rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3">
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Pastikan akses link sudah diset ke "Anyone with the link can view".</p>
                </div>
                
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan untuk Klien (Opsional)</label>
                    <textarea name="result_notes" rows="3" placeholder="Misal: Jangan lupa untuk didownload, kami memberikan akses hingga 1 minggu..." class="w-full text-sm rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3"></textarea>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Pesan ini akan ditampilkan bersamaan dengan link hasil foto.</p>
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <button type="button" @click="submitModalOpen = false" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
