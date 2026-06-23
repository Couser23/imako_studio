@extends('layouts.user', ['title' => 'Jadwal Saya'])

@section('content')
<div class="mb-6 -mt-4">
    <p class="text-sm text-gray-500 font-medium">Pantau dan pastikan Anda datang tepat waktu pada sesi foto Anda.</p>
</div>

<div class="flex flex-col lg:flex-row gap-8 items-start" x-data="myScheduleData()">
    <!-- Calendar Card -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-8 w-full lg:w-[420px] shrink-0">
        <div class="flex items-center justify-between mb-8">
            <button @click="prevMonth" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <h3 class="font-extrabold text-gray-800 text-lg uppercase tracking-wide" x-text="monthName + ' ' + currentYear"></h3>
            <button @click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition">
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
                            'bg-brand-blue text-white shadow-lg shadow-brand-blue/30': selectedDate === day,
                            'text-brand-blue font-extrabold bg-blue-50': selectedDate !== day && isToday(day),
                            'text-gray-600 hover:bg-gray-50': selectedDate !== day && !isToday(day) && !hasSchedule(day),
                            'text-brand-dark font-extrabold hover:bg-gray-50 ring-2 ring-brand-blue ring-inset': selectedDate !== day && !isToday(day) && hasSchedule(day),
                        }"
                        class="w-10 h-10 flex items-center justify-center rounded-[14px] text-sm transition mx-auto relative z-10"
                        x-text="day">
                    </button>
                    <!-- Indicator Dot -->
                    <template x-if="hasSchedule(day) && selectedDate !== day">
                        <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-brand-blue rounded-full z-20"></span>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="w-full flex-1">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-extrabold text-2xl text-brand-dark">Jadwal Sesi <span class="text-brand-blue" x-text="formattedSelectedDate"></span></h3>
            <span class="px-4 py-1.5 bg-gray-100 rounded-full text-xs font-bold text-gray-500" x-text="schedulesForSelectedDate.length + ' Sesi'"></span>
        </div>
        
        <template x-if="schedulesForSelectedDate.length === 0">
            <div class="bg-white border border-gray-100 rounded-[24px] p-16 text-center flex flex-col items-center justify-center shadow-sm">
                <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue mb-5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="text-gray-800 font-extrabold text-lg mb-2">Tidak Ada Sesi</h4>
                <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed">Anda tidak memiliki jadwal sesi foto pada tanggal ini. Pilih tanggal lain atau buat pesanan baru.</p>
                <a href="{{ route('user.bookings.create') }}" class="mt-6 inline-flex items-center justify-center bg-brand-dark hover:bg-brand-blue text-white px-6 py-2.5 rounded-full font-bold text-sm transition shadow-md">
                    Buat Pesanan Baru
                </a>
            </div>
        </template>

        <div class="grid gap-4" x-show="schedulesForSelectedDate.length > 0">
            <template x-for="schedule in schedulesForSelectedDate" :key="schedule.id">
                <div class="bg-white border border-gray-100 rounded-[20px] p-6 shadow-sm hover:shadow-md transition group relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-brand-blue rounded-l-[20px]"></div>
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-brand-blue font-bold text-[10px] rounded-full uppercase tracking-wider" x-text="'#IMK-' + schedule.booking_code"></span>
                            <span class="flex items-center gap-1.5 text-xs font-bold" :class="schedule.status === 'completed' ? 'text-green-600 bg-green-50 px-2 py-0.5 rounded' : (schedule.status === 'in_progress' ? 'text-orange-600 bg-orange-50 px-2 py-0.5 rounded' : 'text-blue-600 bg-blue-50 px-2 py-0.5 rounded')">
                                <span class="w-1.5 h-1.5 rounded-full" :class="schedule.status === 'completed' ? 'bg-green-500' : (schedule.status === 'in_progress' ? 'bg-orange-500' : 'bg-blue-500')"></span>
                                <span x-text="formatStatus(schedule.status)"></span>
                            </span>
                        </div>
                        <h4 class="text-xl font-extrabold text-brand-dark mb-2" x-text="schedule.package_name"></h4>
                        <div class="flex items-center gap-5 text-sm text-gray-500 font-medium">
                            <span class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span x-text="schedule.start_time + ' - ' + schedule.end_time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center md:flex-col gap-3 md:gap-0 md:items-end">
                        <a :href="'/user/bookings'" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 text-brand-blue hover:bg-brand-blue hover:text-white transition shadow-sm border border-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest md:mt-2">Detail Pesanan</span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function myScheduleData() {
        const now = new Date();
        const serverSchedules = @json($userSchedules ?? []);
        
        return {
            today: now,
            currentMonth: now.getMonth(),
            currentYear: now.getFullYear(),
            selectedDate: now.getDate(),
            schedules: serverSchedules,
            
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            
            get monthName() {
                return this.months[this.currentMonth];
            },
            
            get daysInMonth() {
                return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            },
            
            get blankDays() {
                let firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
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
                this.selectedDate = 1;
            },
            
            prevMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
                this.selectedDate = 1;
            },
            
            selectDate(day) {
                this.selectedDate = day;
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

            hasSchedule(day) {
                let dateStr = this.formatDateString(day);
                return this.schedules.some(s => s.booking_date === dateStr);
            },

            get schedulesForSelectedDate() {
                if (!this.selectedDate) return [];
                let dateStr = this.formatDateString(this.selectedDate);
                return this.schedules.filter(s => s.booking_date === dateStr);
            },

            get formattedSelectedDate() {
                if (!this.selectedDate) return '';
                return `${this.selectedDate} ${this.monthName} ${this.currentYear}`;
            },

            formatStatus(status) {
                const map = {
                    'confirmed': 'Terkonfirmasi',
                    'in_progress': 'Sedang Berlangsung',
                    'completed': 'Selesai'
                };
                return map[status] || status;
            }
        }
    }
</script>
@endsection
