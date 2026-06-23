@extends('employee.layouts.app', ['headerTitle' => 'Pengajuan Libur', 'headerSubtitle' => 'Tentukan tanggal libur Anda Disini'])

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 w-full">
    <!-- Form Box -->
    <div class="xl:col-span-1">
        <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-gray-100 dark:border-slate-700 p-8 transition-colors">
            <h3 class="text-sm font-bold text-brand-dark dark:text-white mb-6">Form Pengajuan Libur</h3>
            
            <form action="{{ route('employee.store_pengajuan_libur') }}" method="POST" x-data="{ 
                startDate: '', 
                endDate: '', 
                get totalDays() { 
                    if(!this.startDate || !this.endDate) return 0; 
                    const start = new Date(this.startDate); 
                    const end = new Date(this.endDate); 
                    const diffTime = end - start; 
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    return diffDays >= 0 ? diffDays + 1 : 0; 
                } 
            }">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-brand-dark dark:text-gray-300 mb-2">Tanggal Mulai Libur <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" x-model="startDate" required class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-brand-primary focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-brand-dark font-medium placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                </div>
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-brand-dark dark:text-gray-300 mb-2">Tanggal Selesai Libur <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" x-model="endDate" required class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-brand-primary focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-brand-dark font-medium placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">Isi tanggal yang sama jika hanya 1 hari.</p>
                        <template x-if="totalDays > 0">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-light text-brand-primary border border-blue-100">
                                Total: <span x-text="totalDays" class="mx-1 font-extrabold text-blue-700"></span> Hari
                            </span>
                        </template>
                        <template x-if="totalDays === 0 && startDate && endDate">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-500 border border-red-100">
                                Tanggal tidak valid
                            </span>
                        </template>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-brand-dark dark:text-gray-300 mb-2">Keterangan / Alasan <span class="text-gray-400 dark:text-gray-500 font-normal">(opsional)</span></label>
                    <textarea name="reason" rows="3" placeholder="contoh: Keperluan keluarga..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-brand-primary focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-brand-dark font-medium placeholder-gray-400 dark:placeholder-gray-500 transition-colors"></textarea>
                </div>
                
                <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-xl flex items-start gap-3 transition-colors">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed font-medium">Pengajuan libur akan otomatis ditandai pada jadwal Anda. Pastikan tidak ada sesi yang bentrok.</p>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 dark:border-slate-700">
                    <button type="reset" class="px-5 py-3 text-xs font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-xs font-bold text-white bg-brand-dark dark:bg-blue-600 rounded-xl hover:bg-brand-primary dark:hover:bg-blue-500 shadow-sm transition-colors flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Ajukan Libur
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Riwayat Box -->
    <div class="xl:col-span-2">
        <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-10 transition-colors h-full">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 transition-colors">
                <h3 class="text-sm font-bold text-brand-dark dark:text-white">Riwayat Pengajuan Libur</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Daftar semua pengajuan libur yang pernah Anda buat beserta statusnya.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors">
                            <th class="px-6 py-5 text-xs font-bold text-brand-dark dark:text-gray-300">Tanggal Libur</th>
                            <th class="px-6 py-5 text-xs font-bold text-brand-dark dark:text-gray-300">Keterangan</th>
                            <th class="px-6 py-5 text-xs font-bold text-brand-dark dark:text-gray-300 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($history as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-5 font-bold text-brand-dark dark:text-white">{{ $item['date'] }}</td>
                            <td class="px-6 py-5 text-gray-500 dark:text-gray-400 font-medium text-xs">{{ $item['reason'] }}</td>
                            <td class="px-6 py-5 text-right">
                                @if($item['status'] == 'Disetujui')
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50">
                                        {{ $item['status'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800/50">
                                        {{ $item['status'] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 font-medium">Belum ada riwayat pengajuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
