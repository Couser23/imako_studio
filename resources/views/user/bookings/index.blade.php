@extends('layouts.user', ['title' => 'Status Pesanan'])

@section('content')
<!-- <div class="mb-6 -mt-4">
    <p class="text-sm text-gray-400 font-medium">Pantau progres pesanan dan konfirmasi dari admin</p>
</div> -->

<div x-data="{ showModal: false }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col">
        
        @forelse($bookings as $booking)
        <div x-data="{ showDetailModal: false, showResultModal: false }" class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50 transition cursor-pointer" @click="showDetailModal = true">
            <div>
                <h3 class="text-sm font-extrabold text-gray-800 mb-1">{{ $booking->package->name ?? 'Paket' }}</h3>
                <p class="text-xs text-gray-500 font-medium mb-1">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}, Jam {{ \Carbon\Carbon::parse($booking->start_time)->format('H.i') }}</p>
                <p class="text-[11px] text-gray-400">#IMK-{{ $booking->booking_code }}</p>
            </div>
            <div class="flex flex-col md:items-end gap-2" @click.stop>
                @if($booking->status == 'completed' && $booking->result_link)
                <div class="flex flex-col items-end gap-1">
                    <span class="text-emerald-500 text-[10px] font-extrabold">Hasil Foto Siap</span>
                    <p class="text-sm font-extrabold text-gray-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
                <button @click="showResultModal = true" class="px-4 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded shadow-sm transition">Buka Hasil</button>
                @else
                <span class="px-3 py-1 rounded-full {{ $booking->status == 'pending' ? 'bg-orange-50 text-orange-500 border border-orange-100/50' : ($booking->status == 'completed' ? 'bg-emerald-50 text-emerald-500 border border-emerald-100' : 'bg-blue-50 text-blue-500 border border-blue-100/50') }} text-[10px] font-extrabold shadow-sm">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</span>
                <p class="text-sm font-extrabold text-gray-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                @endif
            </div>

            <!-- Modal Detail Booking -->
            <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" @click.stop>
                <!-- Backdrop -->
                <div x-show="showDetailModal" x-transition.opacity @click="showDetailModal = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                
                <!-- Modal Panel -->
                <!-- Modal Panel -->
                <div x-show="showDetailModal" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-[550px] max-h-[90vh] overflow-hidden flex flex-col">
                    
                    <!-- Header with Gradient -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-brand-dark to-[#2B5488] px-8 py-8">
                        <!-- Abstract Shapes -->
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-blue-400/20 blur-2xl"></div>
                        
                        <div class="relative z-10 flex items-start justify-between">
                            <div>
                                <p class="text-blue-100 text-[11px] font-bold mb-1 tracking-wider uppercase">Detail Pesanan</p>
                                <h3 class="font-extrabold text-white text-2xl">#IMK-{{ $booking->booking_code }}</h3>
                            </div>
                            <button @click="showDetailModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition backdrop-blur-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content Scroll Area -->
                    <div class="overflow-y-auto p-8 pt-6 bg-[#FAFCFF]">
                        <!-- Main Info Card -->
                        <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 mb-5 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-brand-primary"></div>
                            
                            <div class="flex flex-col sm:flex-row justify-between gap-4 mb-5 pb-5 border-b border-gray-50">
                                <div>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Paket yang dipilih</p>
                                    <h4 class="text-base font-extrabold text-brand-dark">{{ $booking->package->name ?? 'Paket' }}</h4>
                                </div>
                                <div class="sm:text-right">
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Status</p>
                                    <span class="inline-block px-3 py-1 {{ $booking->status == 'pending' ? 'bg-orange-50 text-orange-500 border-orange-100/50' : ($booking->status == 'completed' ? 'bg-emerald-50 text-emerald-500 border-emerald-100' : 'bg-blue-50 text-blue-500 border-blue-100/50') }} rounded-full text-[11px] font-extrabold shadow-sm border">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</span>
                                </div>
                            </div>
                            
                            @if($booking->addons && $booking->addons->count() > 0)
                                <div class="mb-5">
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-2">Add-ons Tambahan</p>
                                    <div class="space-y-2">
                                        @foreach($booking->addons as $addon)
                                            <div class="flex justify-between items-center bg-gray-50/50 rounded-xl p-3 border border-gray-100/50">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-lg bg-brand-blue/10 flex items-center justify-center text-brand-blue">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    </div>
                                                    <span class="text-[11px] text-gray-600 font-bold">{{ $addon->name }} x{{ $addon->pivot->quantity }}</span>
                                                </div>
                                                <span class="text-[11px] text-gray-800 font-extrabold">Rp {{ number_format($addon->pivot->price_at_booking * $addon->pivot->quantity, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4 bg-[#F8FAFC] rounded-xl p-4 border border-gray-100">
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Jadwal Sesi</p>
                                    <p class="text-[13px] font-extrabold text-brand-dark flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="text-[11px] font-bold text-gray-500 mt-0.5 ml-5">{{ \Carbon\Carbon::parse($booking->start_time)->format('H.i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Total Pembayaran</p>
                                    <p class="text-lg font-extrabold text-emerald-500">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Conditionally shown sections based on status -->
                        @if(in_array($booking->status, ['approved', 'in_progress', 'completed']))
                            <!-- Link Foto -->
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100/50 mb-5 relative overflow-hidden shadow-sm">
                                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-indigo-500/10 rounded-full blur-xl"></div>
                                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-wider mb-2">Akses Hasil Foto</p>
                                @if($booking->status == 'completed' && $booking->result_link)
                                    <a href="{{ $booking->result_link }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 text-xs font-extrabold rounded-xl shadow-sm border border-indigo-100 hover:bg-indigo-50 hover:border-indigo-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        Buka Google Drive
                                    </a>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                                        <p class="text-xs font-extrabold text-indigo-900">Sedang dalam proses editing...</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Employee Details -->
                            @if($booking->assignments && $booking->assignments->count() > 0)
                                <div class="space-y-3">
                                    @foreach($booking->assignments as $assignment)
                                        @php
                                            $employee = $assignment->employee;
                                        @endphp
                                        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-primary to-brand-dark p-0.5 shrink-0 shadow-sm">
                                                    @if($employee->avatar)
                                                        <img src="{{ asset('images/profile_akun/pegawai/' . $employee->avatar) }}" alt="Fotografer" class="w-full h-full rounded-full border-2 border-white object-cover">
                                                    @else
                                                        <img src="{{ 'https://ui-avatars.com/api/?background=fff&color=2B5488&name=' . urlencode($employee->name) }}" alt="Fotografer" class="w-full h-full rounded-full border-2 border-white object-cover">
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Fotografer Anda</p>
                                                    <h4 class="text-sm font-extrabold text-gray-800">{{ $employee->name }}</h4>
                                                    @php
                                                        $avgRating = $employee->reviewsReceived->avg('rating') ?? 0;
                                                        $ratingCount = $employee->reviewsReceived->count();
                                                    @endphp
                                                    <div class="flex items-center gap-1 mt-0.5 cursor-pointer hover:bg-gray-50 p-1 rounded transition w-fit">
                                                        <svg class="w-3 h-3 text-amber-400 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                        <span class="text-[11px] font-bold text-gray-600">{{ number_format($avgRating, 1) }} <span class="font-normal text-gray-400">({{ $ratingCount }} ulasan)</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($booking->status == 'completed')
                                            <a href="{{ route('user.results.index') }}" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition group cursor-pointer">
                                                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                                </div>
                                                <span class="text-[9px] font-bold text-gray-500 group-hover:text-amber-600">Beri Ulasan</span>
                                            </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Footer -->
                    <div class="p-6 border-t border-gray-100 flex justify-end">
                        <button @click="showDetailModal = false" class="px-8 py-2.5 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Buka Hasil Foto (only if ready) -->
            @if($booking->status == 'completed' && $booking->result_link)
            <div x-show="showResultModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-0">
                <div x-show="showResultModal" x-transition.opacity @click="showResultModal = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                <div x-show="showResultModal" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-[480px]">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
                        <h3 class="text-sm font-extrabold text-gray-800">Buka Hasil Foto</h3>
                        <button @click="showResultModal = false" type="button" class="text-gray-400 hover:text-gray-600 transition border border-gray-200 rounded-full p-1 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-6 bg-white flex flex-col items-center">
                        <div class="w-full bg-[#E8F8EE] border border-[#A7E6C0] rounded-xl p-4 flex items-start gap-3 mb-6 shadow-sm">
                            <div class="w-5 h-5 bg-[#22C55E] text-white rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-extrabold text-gray-800 mb-1">Hasil Foto sudah siap !</h4>
                                <p class="text-[11px] font-bold text-gray-700">{{ $booking->package->name }} - {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }} - #IMK-{{ $booking->booking_code }}</p>
                            </div>
                        </div>
                        <div class="w-full bg-white border border-gray-200 rounded-xl p-4 shadow-sm text-center mb-6 relative">
                            <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-white px-2 text-[10px] font-bold text-gray-400">Link Google Drive :</span>
                            <a href="{{ $booking->result_link }}" target="_blank" class="text-xs font-extrabold text-gray-800 underline underline-offset-2 decoration-gray-300 break-all">{{ $booking->result_link }}</a>
                        </div>
                        <p class="text-[11px] font-bold text-gray-400 mb-6">Pastikan untuk mendownload semua foto Anda.</p>
                        <a href="{{ $booking->result_link }}" target="_blank" class="w-full py-3 bg-[#22C55E] hover:bg-green-600 text-white text-xs font-extrabold rounded-xl shadow-md shadow-green-500/20 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Buka di Google Drive
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="p-10 text-center text-gray-500">
            Belum ada pesanan terbaru.
        </div>
        @endforelse

    </div>
</div>


</div>
@endsection
