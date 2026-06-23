@extends('employee.layouts.app', ['headerTitle' => 'Jadwal Saya', 'headerSubtitle' => 'Timeline sesi & jeda hari ini'])

@section('content')

@php
    $openTime = $studioSetting->open_time ?? '09:00:00';
    $closeTime = $studioSetting->close_time ?? '19:00:00';
    $blocks = [];

    foreach($assignments as $assignment) {
        $sessionStart = \Carbon\Carbon::parse($assignment->booking->start_time);
        $sessionStart->setDate($carbonDate->year, $carbonDate->month, $carbonDate->day);
        
        $sessionEnd = \Carbon\Carbon::parse($assignment->booking->end_time);
        $sessionEnd->setDate($carbonDate->year, $carbonDate->month, $carbonDate->day);
        
        $now = now();
        $isPast = $sessionEnd < $now;
        $isNow = $sessionStart <= $now && $sessionEnd >= $now;
        
        $blocks[] = [
            'type' => 'sesi',
            'time' => $sessionStart->format('H.i'),
            'startFmt' => $sessionStart->format('H.i'),
            'endFmt' => $sessionEnd->format('H.i'),
            'clientName' => $assignment->booking->user->name ?? 'Klien',
            'packageName' => $assignment->booking->package->name ?? 'Paket',
            'bookingId' => $assignment->booking->id,
            'bookingCode' => $assignment->booking->booking_code ?? '-',
            'notes' => $assignment->booking->notes ?? 'Tidak ada catatan tambahan.',
            'resultLink' => $assignment->booking->result_link ?? '',
            'resultNotes' => $assignment->booking->result_notes ?? '',
            'duration' => $sessionStart->diffInMinutes($sessionEnd),
            'jeda' => $jedaDuration,
            'isPast' => $isPast,
            'isNow' => $isNow,
            'status' => $assignment->booking->status
        ];
        
        $jedaEnd = (clone $sessionEnd)->addMinutes($jedaDuration);
        
        $blocks[] = [
            'type' => 'jeda',
            'time' => $sessionEnd->format('H.i'),
            'duration' => $jedaDuration,
            'isPast' => $jedaEnd < $now,
            'isNow' => $sessionEnd <= $now && $jedaEnd >= $now
        ];
    }
@endphp

<div x-data="myScheduleData()" class="flex flex-col lg:flex-row gap-8 items-start">

<!-- Calendar Card -->
<div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-8 w-full lg:w-[380px] shrink-0">
    <div class="flex items-center justify-between mb-8">
        <button @click="prevMonth" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <h3 class="font-extrabold text-gray-800 text-lg uppercase tracking-wide" x-text="monthName + ' ' + currentYear"></h3>
        <button @click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
    
    <div class="grid grid-cols-7 text-center mb-6">
        <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']" :key="day">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider" x-text="day"></span>
        </template>
    </div>
    
    <div class="grid grid-cols-7 text-center gap-y-4 gap-x-2">
        <!-- Blank days -->
        <template x-for="blank in blankDays" :key="'blank-'+blank">
            <div></div>
        </template>
        
        <!-- Actual days -->
        <template x-for="day in days" :key="'day-'+day">
            <div class="relative">
                <button 
                    @click="selectDate(day)"
                    :class="{
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold': isSelectedDate(day),
                        'text-blue-600 font-extrabold bg-blue-50': !isSelectedDate(day) && isToday(day),
                        'text-gray-600 hover:bg-gray-50': !isSelectedDate(day) && !isToday(day) && !hasSchedule(day),
                        'text-gray-800 font-extrabold hover:bg-gray-50 ring-2 ring-blue-500 ring-inset': !isSelectedDate(day) && !isToday(day) && hasSchedule(day),
                    }"
                    class="w-10 h-10 flex items-center justify-center rounded-[14px] text-sm transition mx-auto relative z-10"
                    x-text="day">
                </button>
                <!-- Indicator Dot for tasks -->
                <template x-if="hasSchedule(day) && !isSelectedDate(day)">
                    <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-blue-500 rounded-full z-20"></span>
                </template>
            </div>
        </template>
    </div>
    
    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col gap-3">
        <div class="flex items-center gap-3 text-xs text-gray-500 font-medium">
            <span class="w-3 h-3 rounded bg-blue-600 shadow-sm"></span> Tanggal terpilih
        </div>
        <div class="flex items-center gap-3 text-xs text-gray-500 font-medium">
            <span class="w-3 h-3 rounded ring-2 ring-blue-500 ring-inset"></span> Ada tugas foto
        </div>
        <div class="flex items-center gap-3 text-xs text-gray-500 font-medium">
            <span class="w-3 h-3 rounded bg-blue-50"></span> Hari ini
        </div>
    </div>
</div>

<div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden mb-8 flex-1 w-full relative">
   <!-- Header bar -->
   <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
       <div class="flex items-center gap-3">
           <a href="?date={{ $carbonDate->copy()->subDay()->format('Y-m-d') }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
           </a>
           <span class="font-bold text-brand-dark text-[14px] min-w-[150px] text-center">{{ $carbonDate->translatedFormat('l, d M Y') }}</span>
           <a href="?date={{ $carbonDate->copy()->addDay()->format('Y-m-d') }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
           </a>
           <a href="?date={{ now()->format('Y-m-d') }}" class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition-colors ml-2">Hari ini</a>
       </div>
       <div class="flex gap-4 text-[10px] font-bold text-gray-400 uppercase tracking-wide">
           <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 bg-blue-500 rounded-sm"></div> Sesi foto</div>
           <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 bg-yellow-400 rounded-sm"></div> Jeda</div>
       </div>
   </div>
   
   <div class="p-8 flex flex-col relative z-0">
       <!-- Vertical line connecting timeline -->
       <div class="absolute left-[99px] top-8 bottom-8 w-px bg-gray-100 -z-10 hidden sm:block"></div>
       
       @forelse($blocks as $block)
           @if($block['type'] === 'sesi')
               <div @click="openDetail({{ json_encode($block) }})" class="cursor-pointer hover:shadow-md transition-shadow flex items-stretch mb-3 group relative">
                   <div class="w-16 text-blue-500 font-bold text-xs pt-3 shrink-0">{{ $block['time'] }}</div>
                   <div class="w-1.5 bg-blue-500 rounded-l-md relative">
                       @if($block['status'] === 'in_progress')
                           <!-- Blinking dot on the timeline bar -->
                           <div class="absolute top-3 -left-1 w-3.5 h-3.5 bg-blue-500 rounded-full animate-ping opacity-75"></div>
                           <div class="absolute top-3 -left-1 w-3.5 h-3.5 bg-blue-600 rounded-full border-2 border-white"></div>
                       @endif
                   </div>
                   <div class="flex-1 bg-brand-light px-6 py-4 rounded-r-md border border-blue-100 border-l-0 flex justify-between items-start shadow-sm group-hover:bg-blue-100/50 transition-colors">
                       <div>
                           <h4 class="text-brand-dark font-bold text-sm mb-1.5">{{ $block['clientName'] }} . - {{ $block['packageName'] }}</h4>
                           <p class="text-blue-500 text-[11px] font-bold">{{ $block['startFmt'] }} - {{ $block['endFmt'] }} &middot; {{ $block['duration'] }} menit &middot; Jeda {{ $block['jeda'] }} mnt sesudahnya</p>
                       </div>
                       @if($block['status'] === 'completed')
                           <span class="text-gray-500 font-bold text-[10px] bg-gray-100 border border-gray-200 px-3 py-1 rounded-md transition-colors">Selesai</span>
                       @elseif($block['status'] === 'in_progress')
                           <span class="flex items-center gap-1.5 text-blue-700 font-bold text-[10px] bg-blue-100 border border-blue-200 px-3 py-1 rounded-md transition-colors">
                               <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                               Sedang Berlangsung
                           </span>
                       @else
                           <span class="text-blue-600 font-bold text-[10px] bg-blue-50 border border-blue-100 px-3 py-1 rounded-md transition-colors">Menunggu</span>
                       @endif
                   </div>
               </div>
           @elseif($block['type'] === 'jeda')
               <div class="flex items-stretch mb-3">
                   <div class="w-16 text-yellow-500 font-bold text-xs pt-3 shrink-0">{{ $block['time'] }}</div>
                   <div class="w-1.5 border-l-2 border-dashed border-yellow-400 rounded-l-md"></div>
                   <div class="flex-1 bg-yellow-50/80 px-6 py-4 rounded-r-md border border-yellow-100 border-l-0 shadow-sm relative">
                       <div class="flex items-center gap-1.5 text-yellow-600 font-bold text-xs mb-1.5">
                           <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                           Jeda {{ $block['duration'] }} mnt
                       </div>
                       <p class="text-yellow-600/70 text-[11px] font-medium">Persiapan studio & istirahat</p>
                       @if($block['isPast'])
                           <span class="absolute top-4 right-6 text-gray-500 font-bold text-[10px] bg-gray-100 border border-gray-200 px-3 py-1 rounded-md transition-colors">Selesai</span>
                       @elseif($block['isNow'])
                           <span class="absolute top-4 right-6 flex items-center gap-1.5 text-yellow-700 font-bold text-[10px] bg-yellow-100 border border-yellow-200 px-3 py-1 rounded-md transition-colors">
                               <span class="w-1.5 h-1.5 rounded-full bg-yellow-600 animate-pulse"></span>
                               Sedang Berlangsung
                           </span>
                       @endif
                   </div>
               </div>
           @endif
       @empty
           <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
               <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
                   <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
               </div>
               <h4 class="text-gray-800 font-bold text-lg mb-2">Tidak ada tugas</h4>
               <p class="text-gray-500 text-sm max-w-sm">Anda tidak memiliki tugas foto untuk tanggal ini. Silakan pilih tanggal lain di kalender.</p>
           </div>
       @endforelse
   </div>
</div>

<!-- Modal Detail Sesi -->
<div x-show="detailModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm" x-cloak>
    <div @click.outside="detailModalOpen = false" class="bg-white rounded-[24px] shadow-xl w-full max-w-lg overflow-hidden flex flex-col transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-brand-dark font-bold text-lg">Detail Sesi &mdash; <span x-text="selectedSession.clientName"></span></h3>
            <button @click="detailModalOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">Klien</p>
                    <p class="text-brand-dark font-bold text-sm" x-text="selectedSession.clientName"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">No. Booking</p>
                    <p class="text-brand-dark font-bold text-sm" x-text="'#IMK-' + (selectedSession.bookingCode || 'TIDAK ADA')"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">Paket</p>
                    <p class="text-brand-dark font-bold text-sm" x-text="selectedSession.packageName"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">Jam Sesi</p>
                    <p class="text-brand-dark font-bold text-sm"><span x-text="selectedSession.startFmt"></span> - <span x-text="selectedSession.endFmt"></span></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">Durasi</p>
                    <p class="text-brand-dark font-bold text-sm"><span x-text="selectedSession.duration"></span> menit</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">Jeda setelah sesi</p>
                    <p class="text-brand-primary font-bold text-sm"><span x-text="selectedSession.jeda"></span> menit</p>
                </div>
            </div>
            


            <div class="bg-gray-50 rounded-xl p-4 mb-8 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] text-gray-400 font-bold mb-1 uppercase tracking-wide">Status Sesi</p>
                    <p class="text-xs text-gray-500 font-medium">Ubah status sesi saat ini</p>
                </div>
                <form method="POST" :action="`{{ url('/pegawai/booking') }}/${selectedSession.bookingId}/status`" class="flex items-stretch gap-2">
                    @csrf
                    @method('PUT')
                    <select name="status" class="text-xs border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 font-bold focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer" x-model="selectedSession.status">
                        <option value="pending" x-show="selectedSession.status == 'pending'">Menunggu</option>
                        <option value="confirmed" x-show="selectedSession.status == 'confirmed'">Menunggu</option>
                        <option value="in_progress">Sedang Berlangsung</option>
                        <option value="completed">Selesai</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-lg hover:bg-blue-700 transition-colors">Simpan</button>
                </form>
            </div>
            
            <div class="flex justify-end gap-3">
                <button @click="detailModalOpen = false" class="px-6 py-3 border border-gray-200 text-gray-600 font-bold text-xs rounded-xl hover:bg-gray-50 transition-colors">Tutup</button>
                <button @click="openSubmit()" class="px-6 py-3 bg-brand-dark text-white font-bold text-xs rounded-xl hover:bg-brand-primary transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Hasil
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kirim Hasil -->
<div x-show="submitModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm" x-cloak>
    <div @click.outside="submitModalOpen = false" class="bg-white rounded-[24px] shadow-xl w-full max-w-md p-8 relative z-10 mx-4 transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-brand-dark">Kirim Hasil Foto</h3>
            <button @click="submitModalOpen = false" class="text-gray-400 hover:text-gray-500 bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="mb-6 p-4 bg-brand-light border border-blue-100 rounded-xl">
            <p class="text-sm text-brand-dark">Sesi untuk klien <span x-text="selectedSession.clientName" class="font-bold text-brand-primary"></span> (<span x-text="selectedSession.packageName" class="text-brand-primary font-medium"></span>).</p>
        </div>
        
        <form :action="'{{ url('pegawai/booking') }}/' + selectedSession.bookingId + '/result'" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tautan (Link) Google Drive</label>
                <input type="url" name="result_link" x-model="selectedSession.resultLink" placeholder="https://drive.google.com/..." required class="w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 font-medium text-brand-dark mb-2">
                <p class="text-xs text-gray-500">Pastikan akses link sudah diset ke "Anyone with the link can view".</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Catatan untuk Klien (Opsional)</label>
                <textarea name="result_notes" x-model="selectedSession.resultNotes" rows="3" placeholder="Tambahkan pesan atau instruksi untuk klien mengenai hasil foto ini..." class="w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-brand-primary focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 font-medium text-brand-dark"></textarea>
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="submitModalOpen = false" class="px-6 py-3 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-3 text-xs font-bold text-white bg-brand-dark rounded-xl hover:bg-brand-primary shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection

<script>
    function myScheduleData() {
        const now = new Date();
        const serverSchedules = @json($allAssignments ?? []);
        // Get the viewed date from PHP
        const viewedDateStr = '{{ $carbonDate->format('Y-m-d') }}';
        const viewedDateParts = viewedDateStr.split('-');
        
        return {
            today: now,
            currentMonth: parseInt(viewedDateParts[1]) - 1,
            currentYear: parseInt(viewedDateParts[0]),
            viewedDay: parseInt(viewedDateParts[2]),
            schedules: serverSchedules,
            detailModalOpen: false, 
            submitModalOpen: false,
            selectedSession: {},
            
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            
            get monthName() {
                return this.months[this.currentMonth];
            },
            
            get daysInMonth() {
                return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            },
            
            get blankDays() {
                let firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
                // Adjust if you want Monday as first day, currently Sunday is 0
                return Array.from({ length: firstDayOfMonth }, (_, i) => i);
            },
            
            get days() {
                return Array.from({ length: this.daysInMonth }, (_, i) => i + 1);
            },
            
            nextMonth() {
                if (this.currentMonth === 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
            },
            
            prevMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
            },
            
            selectDate(day) {
                // Navigate to the selected date
                window.location.href = '?date=' + this.formatDateString(day);
            },

            formatDateString(day) {
                let m = String(this.currentMonth + 1).padStart(2, '0');
                let d = String(day).padStart(2, '0');
                return `${this.currentYear}-${m}-${d}`;
            },

            isToday(day) {
                return day === this.today.getDate() && 
                       this.currentMonth === this.today.getMonth() && 
                       this.currentYear === this.today.getFullYear();
            },

            isSelectedDate(day) {
                return day === this.viewedDay && 
                       this.currentMonth === parseInt(viewedDateParts[1]) - 1 && 
                       this.currentYear === parseInt(viewedDateParts[0]);
            },

            hasSchedule(day) {
                let dateStr = this.formatDateString(day);
                return this.schedules.some(s => s.booking_date === dateStr);
            },
            
            openDetail(session) {
                this.selectedSession = session;
                this.detailModalOpen = true;
            },
            
            openSubmit() {
                this.detailModalOpen = false;
                this.submitModalOpen = true;
            }
        }
    }
</script>
