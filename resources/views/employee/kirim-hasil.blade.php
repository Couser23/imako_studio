@extends('employee.layouts.app', ['headerTitle' => 'Kirim Hasil', 'headerSubtitle' => 'Upload link hasil foto ke klien'])

@section('content')

<div x-data="{ 
    submitModalOpen: false,
    selectedSession: {},
    openSubmit(task) {
        this.selectedSession = task;
        this.submitModalOpen = true;
    }
}">

<!-- BELUM DIKIRIM -->

<div class="mb-10">
    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Belum Dikirim ({{ count($pendingResults) }})</h3>
    <div class="flex flex-col gap-3">
        @forelse($pendingResults as $task)
            <div class="bg-white rounded-xl border border-yellow-300 shadow-sm p-5 flex items-center justify-between">
                <div>
                    <h4 class="text-brand-dark font-bold text-sm mb-1.5">{{ $task['clientName'] }}</h4>
                    <p class="text-gray-400 text-[11px] font-medium">{{ $task['packageName'] }} &middot; {{ $task['dateFmt'] }} &middot; {{ $task['bookingNo'] }}</p>
                </div>
                <button @click="openSubmit({{ json_encode($task) }})" class="px-5 py-2.5 bg-brand-dark text-white text-[11px] font-bold rounded-lg hover:bg-brand-primary transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Hasil
                </button>
            </div>
        @empty
            <div class="text-center p-8 bg-white rounded-xl border border-gray-100 text-gray-500 font-medium">Semua hasil sudah terkirim!</div>
        @endforelse
    </div>
</div>

<!-- SUDAH DIKIRIM -->
<div>
    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Sudah Dikirim ({{ count($sentResults) }})</h3>
    <div class="flex flex-col gap-3">
        @forelse($sentResults as $task)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
                <div>
                    <h4 class="text-brand-dark font-bold text-sm mb-1.5">{{ $task['clientName'] }}</h4>
                    <p class="text-gray-400 text-[11px] font-medium">{{ $task['packageName'] }} &middot; {{ $task['bookingNo'] }}</p>
                </div>
                <div class="flex flex-col items-end gap-1.5">
                    <span class="text-green-700 font-bold text-[10px] bg-green-100 px-3 py-1 rounded-md flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Terkirim
                    </span>
                    <span class="text-gray-400 text-[10px] font-medium">{{ $task['sentAt'] }}</span>
                </div>
            </div>
        @empty
            <div class="text-center p-8 bg-white rounded-xl border border-gray-100 text-gray-500 font-medium">Belum ada hasil yang dikirim.</div>
        @endforelse
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
