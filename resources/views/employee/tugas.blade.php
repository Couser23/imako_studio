@extends('employee.layouts.app', ['headerTitle' => 'Tugas Saya', 'headerSubtitle' => 'Daftar sesi yang di-assign ke kamu'])

@section('content')

<div x-data="{ 
    activeTab: 'menunggu',
    detailModalOpen: false, 
    submitModalOpen: false,
    selectedSession: {},
    openDetail(task) {
        this.selectedSession = task;
        this.detailModalOpen = true;
    },
    openSubmit(task = null) {
        if (task) {
            this.selectedSession = task;
        }
        this.detailModalOpen = false;
        this.submitModalOpen = true;
    }
}">

<div class="flex flex-col sm:flex-row gap-4 mb-6">
    <form method="GET" action="{{ route('employee.tugas') }}" class="flex flex-col sm:flex-row gap-3 w-full">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama klien atau #IMK..." class="w-full bg-white border border-gray-100 text-brand-dark text-xs font-bold rounded-2xl pl-10 pr-4 py-3.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-400 shadow-sm" onchange="this.form.submit()">
        </div>
        
        <div class="relative w-full sm:w-48 shrink-0">
            <select name="date_filter" onchange="this.form.submit()" class="w-full appearance-none bg-white border border-gray-100 text-brand-dark text-xs font-bold rounded-2xl px-4 py-3.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-10 shadow-sm">
                <option value="">Semua Waktu</option>
                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-brand-dark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
        
        <button type="submit" class="hidden">Cari</button>
    </form>
    
    @if(request('search') || request('date_filter'))
    <a href="{{ route('employee.tugas') }}" class="px-5 py-3.5 bg-red-50 text-red-600 text-xs font-bold rounded-2xl hover:bg-red-100 transition-colors flex items-center justify-center shrink-0 shadow-sm">
        Reset
    </a>
    @endif
</div>

<div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden mb-8">
    <!-- Tabs -->
    <div class="flex items-center gap-6 border-b border-gray-100 px-8">
        <button @click="activeTab = 'menunggu'" :class="activeTab === 'menunggu' ? 'border-brand-dark text-brand-dark font-bold' : 'border-transparent text-gray-400 hover:text-gray-600 font-medium'" class="py-5 border-b-2 flex items-center gap-2 transition-colors">
            Menunggu <span class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-bold" :class="activeTab === 'menunggu' ? 'bg-brand-dark text-white' : 'bg-gray-100 text-gray-500'">{{ count($pendingTasks) }}</span>
        </button>
        <button @click="activeTab = 'selesai'" :class="activeTab === 'selesai' ? 'border-brand-dark text-brand-dark font-bold' : 'border-transparent text-gray-400 hover:text-gray-600 font-medium'" class="py-5 border-b-2 flex items-center gap-2 transition-colors">
            Selesai <span class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-bold" :class="activeTab === 'selesai' ? 'bg-brand-dark text-white' : 'bg-gray-100 text-gray-500'">{{ count($completedTasks) }}</span>
        </button>
    </div>

    <!-- Tab Content: Menunggu -->
    <div x-show="activeTab === 'menunggu'" class="flex flex-col">
        @forelse($pendingTasks as $task)
            <div @click="openDetail({{ json_encode($task) }})" class="cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-50 px-8 py-6 flex items-center justify-between group last:border-b-0">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-brand-light text-brand-primary flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-brand-dark font-bold text-sm mb-1">{{ $task['clientName'] }}</h4>
                        <p class="text-gray-400 text-xs mb-1">{{ $task['packageName'] }} &middot; {{ $task['dateFmt'] }} &middot; {{ $task['startFmt'] }} - {{ $task['endFmt'] }}</p>
                        <p class="text-gray-400 text-xs font-medium">{{ $task['bookingNo'] }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-3">
                    @if($task['status'] == 'in_progress')
                        <span class="text-green-600 font-bold text-[10px] bg-green-100 px-3 py-1 rounded-md">Sedang Berlangsung</span>
                    @elseif($task['status'] == 'completed')
                        <span class="text-gray-500 font-bold text-[10px] bg-gray-100 px-3 py-1 rounded-md">Selesai</span>
                    @else
                        <span class="text-blue-600 font-bold text-[10px] bg-blue-100 px-3 py-1 rounded-md">Menunggu</span>
                    @endif
                    <button @click.stop="openSubmit({{ json_encode($task) }})" class="px-4 py-2 bg-gray-100 text-brand-dark text-[11px] font-bold rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim hasil
                    </button>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500 font-medium">Tidak ada tugas yang menunggu.</div>
        @endforelse
    </div>

    <!-- Tab Content: Selesai -->
    <div x-show="activeTab === 'selesai'" class="flex flex-col" style="display: none;" x-cloak>
        @forelse($completedTasks as $task)
            <div @click="openDetail({{ json_encode($task) }})" class="cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-50 px-8 py-6 flex items-center justify-between group last:border-b-0">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0 border border-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-brand-dark font-bold text-sm mb-1">{{ $task['clientName'] }}</h4>
                        <p class="text-gray-400 text-[11px] font-medium mb-1">{{ $task['packageName'] }} &middot; {{ $task['dateFmt'] }} &middot; {{ $task['startFmt'] }} - {{ $task['endFmt'] }}</p>
                        <p class="text-gray-400 text-[11px] font-medium">{{ $task['bookingNo'] }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-3">
                    <span class="text-gray-400 font-bold text-[10px] bg-gray-50 border border-gray-100 px-4 py-1.5 rounded-lg">Selesai</span>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500 font-medium">Belum ada tugas yang diselesaikan.</div>
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
                    <p class="text-brand-dark font-bold text-sm" x-text="selectedSession.bookingNo"></p>
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
            


            <template x-if="!selectedSession.isSent">
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
            </template>
            
            <div class="flex justify-end gap-3">
                <button @click="detailModalOpen = false" class="px-6 py-3 border border-gray-200 text-gray-600 font-bold text-xs rounded-xl hover:bg-gray-50 transition-colors">Tutup</button>
                <template x-if="!selectedSession.isSent">
                    <button @click="openSubmit()" class="px-6 py-3 bg-brand-dark text-white font-bold text-xs rounded-xl hover:bg-brand-primary transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Hasil
                    </button>
                </template>
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
