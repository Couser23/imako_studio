@extends('layouts.user', ['title' => 'Dashboard'])

@section('content')
    <div class="flex flex-col gap-6 mt-6">
        
        <!-- Hero Section -->
        <div class="w-full bg-gradient-to-r from-brand-dark via-brand-blue to-[#4084D1] rounded-[24px] p-10 relative overflow-hidden flex items-center shadow-lg">
            <!-- Background Image Overlay (Simulated with patterns or gradients) -->
            <div class="absolute inset-0 opacity-20 mix-blend-overlay bg-cover bg-center" style="background-image: url('{{ asset('images/bg.jpg') }}');"></div>
            
            <div class="relative z-10 text-white max-w-xl">
                <p class="text-sm font-semibold text-blue-200 mb-2">Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋</p>
                <h2 class="text-3xl md:text-4xl font-extrabold leading-tight mb-2">Abadikan Momenmu<br/>Bersama Imako Studio</h2>
                <p class="text-blue-100 text-sm mb-6">Tersedia untuk booking mulai besok</p>
                <a href="{{ route('user.bookings.create') }}" class="inline-flex items-center gap-2 bg-white text-brand-dark px-6 py-2.5 rounded-full font-bold text-sm hover:bg-gray-50 transition shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Booking Sekarang
                </a>
            </div>
            
            <div class="absolute right-12 top-1/2 -translate-y-1/2 hidden lg:flex items-center justify-center">
                <img src="{{ asset('images/logo.PNG') }}" alt="Logo Imako Studio" class="w-28 h-28 object-contain drop-shadow-xl">
            </div>
        </div>

        <!-- Active Booking Alert -->
        @if($activeBooking)
        <div class="bg-[#FFF8ED] border border-[#FBE5C5] rounded-[20px] p-5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-[#FFE5B2] flex items-center justify-center text-[#D97706] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Booking aktif : {{ $activeBooking->package->name ?? 'Paket' }} - {{ \Carbon\Carbon::parse($activeBooking->booking_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($activeBooking->start_time)->format('H.i') }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Status: {{ ucwords(str_replace('_', ' ', $activeBooking->status)) }} · #IMK-{{ $activeBooking->booking_code }}</p>
                </div>
            </div>
            <a href="{{ route('user.bookings.index') }}" class="px-4 py-2 bg-white border border-[#FBE5C5] text-[#D97706] rounded-full text-xs font-bold hover:bg-[#FFE5B2]/20 transition shrink-0">Lihat detail</a>
        </div>
        @endif

        <!-- Two Columns Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Paket & Riwayat -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                <!-- Paket Foto Kami -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-bold text-gray-800 text-lg">Paket Foto Kami</h3>
                        <a href="{{ route('user.catalog.index') }}" class="px-4 py-1.5 bg-gray-50 text-brand-dark rounded-full text-xs font-bold hover:bg-gray-100 transition">Booking →</a>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($packages as $package)
                        <!-- Card -->
                        <a href="{{ route('user.catalog.index', ['search' => $package->name]) }}" class="block h-40 rounded-[20px] relative overflow-hidden group cursor-pointer">
                            <img src="{{ $package->image ? asset('images/paket/' . $package->image) : 'https://ui-avatars.com/api/?name=' . urlencode($package->name) . '&background=F1F5F9&color=2B5488&size=512' }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex flex-col justify-end p-5">
                                <h4 class="text-white font-bold text-sm">{{ $package->name }}</h4>
                                <p class="text-gray-300 text-[10px] mb-2">{{ Str::limit($package->description ?? 'Paket foto', 40) }}</p>
                                <div class="flex items-center gap-3">
                                    <span class="text-yellow-400 font-bold text-sm">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    <span class="text-gray-300 text-[10px]">{{ $package->duration_minutes }} menit</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column: Hasil Foto Widget -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="p-5 border-b border-gray-50 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <h3 class="font-bold text-gray-800 text-sm">Hasil foto siap!</h3>
                    </div>
                    
                    <div class="p-5">
                        @if($latestResult)
                        <div x-data="{ showModal: false }" class="border border-green-100 bg-green-50/30 rounded-[16px] p-5">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $latestResult->package->name ?? 'Paket' }}</h4>
                            <p class="text-xs text-gray-500 mb-4">{{ \Carbon\Carbon::parse($latestResult->booking_date)->translatedFormat('d M Y') }} · #IMK-{{ $latestResult->booking_code }}</p>
                            
                            <button @click="showModal = true" class="block w-full py-2.5 bg-green-500 hover:bg-green-600 text-white text-center rounded-xl text-sm font-bold transition shadow-sm shadow-green-500/20 mb-3">
                                <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                Buka Hasil Foto
                            </button>

                            <!-- Modal Backdrop & Container -->
                            <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
                                <!-- Backdrop -->
                                <div x-show="showModal" x-transition.opacity @click="showModal = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm text-left"></div>
                                
                                <!-- Modal Panel -->
                                <div x-show="showModal" 
                                     x-transition:enter="transition ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                     x-transition:leave="transition ease-in duration-200" 
                                     x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                     x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-[500px] overflow-hidden flex flex-col text-left">
                                    
                                    <!-- Header -->
                                    <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100">
                                        <h3 class="font-extrabold text-brand-dark text-lg">Buka Hasil Foto</h3>
                                        <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:bg-gray-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Content Area -->
                                    <div class="p-8 pt-6">
                                        
                                        <!-- Alert Box -->
                                        <div class="bg-[#ECFDF5] border border-[#A7F3D0] rounded-[16px] p-5 mb-6 flex items-start gap-3">
                                            <svg class="w-5 h-5 text-[#10B981] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-[13px] mb-1">Hasil Foto sudah siap !</h4>
                                                <p class="text-[12px] font-bold text-gray-700">{{ $latestResult->package->name ?? 'Paket' }} - {{ \Carbon\Carbon::parse($latestResult->booking_date)->translatedFormat('d M Y') }} - #IMK-{{ $latestResult->booking_code }}</p>
                                            </div>
                                        </div>

                                        <!-- Link Box -->
                                        <div class="bg-gray-50 border border-gray-200 rounded-[16px] p-5 mb-6 text-center">
                                            <p class="text-[11px] font-semibold text-gray-400 mb-2">Link Google Drive :</p>
                                            <a href="{{ $latestResult->result_link }}" target="_blank" class="text-sm font-bold text-gray-800 underline decoration-gray-400 underline-offset-4 hover:text-brand-blue transition break-all">
                                                {{ $latestResult->result_link }}
                                            </a>
                                        </div>

                                        <!-- Info Text -->
                                        <p class="text-center text-[12px] text-gray-500 mb-6">
                                            Pastikan untuk mendownload semua foto Anda.
                                        </p>

                                        <!-- Action Button -->
                                        <a href="{{ $latestResult->result_link }}" target="_blank" class="block w-full py-3.5 bg-[#10B981] hover:bg-[#059669] text-white text-center rounded-xl text-sm font-bold transition shadow-sm shadow-green-500/20">
                                            Buka di Google Drive
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-10 text-gray-500 text-sm">
                            Belum ada hasil foto terbaru.
                        </div>
                        @endif
                        
                        <div class="mt-5 text-center">
                            <a href="{{ route('user.results.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-blue transition">Lihat semua hasil foto →</a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Full Width Column: Riwayat Booking Terakhir -->
            <div class="lg:col-span-3">
                <!-- Riwayat Booking Terakhir -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-bold text-gray-800 text-lg">Riwayat Booking Terakhir</h3>
                        <a href="{{ route('user.bookings.index') }}" class="px-4 py-1.5 bg-gray-50 text-gray-600 rounded-full text-xs font-bold hover:bg-gray-100 transition">Lihat semua</a>
                    </div>
                    
                    <div class="flex flex-col gap-4">
                        @forelse($recentBookings as $booking)
                        <!-- Item with Modal -->
                        <div x-data="{ showModal: false }" class="pb-4 border-b border-gray-50 last:border-0">
                            <!-- List Item -->
                            <div @click="showModal = true" class="flex items-center justify-between p-2 -mx-2 rounded-xl transition cursor-pointer hover:bg-gray-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 overflow-hidden shrink-0">
                                        <img src="{{ $booking->package->image ? asset('images/paket/' . $booking->package->image) : 'https://ui-avatars.com/api/?name=' . urlencode($booking->package->name) . '&background=F1F5F9&color=2B5488&size=512' }}" alt="img" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">{{ $booking->package->name ?? 'Paket' }}</h4>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }} · {{ \Carbon\Carbon::parse($booking->start_time)->format('H.i') }} · #IMK-{{ $booking->booking_code }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold mb-1 shadow-sm">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</span>
                                    <p class="text-xs text-gray-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Modal Backdrop & Container -->
                            <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
                                <!-- Backdrop -->
                                <div x-show="showModal" x-transition.opacity @click="showModal = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                                
                                <!-- Modal Panel -->
                                <div x-show="showModal" 
                                     x-transition:enter="transition ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                     x-transition:leave="transition ease-in duration-200" 
                                     x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                     x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-[550px] max-h-[90vh] overflow-hidden flex flex-col">
                                    
                                    <!-- Header with Gradient -->
                                    <div class="relative overflow-hidden bg-gradient-to-r from-brand-dark to-[#2B5488] px-8 py-8 shrink-0">
                                        <!-- Abstract Shapes -->
                                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
                                        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-blue-400/20 blur-2xl"></div>
                                        
                                        <div class="relative z-10 flex items-start justify-between">
                                            <div>
                                                <p class="text-blue-100 text-[11px] font-bold mb-1 tracking-wider uppercase">Detail Pesanan</p>
                                                <h3 class="font-extrabold text-white text-2xl">#IMK-{{ $booking->booking_code }}</h3>
                                            </div>
                                            <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition backdrop-blur-sm">
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
                                        <a href="{{ route('user.bookings.index') }}" class="px-8 py-2.5 bg-brand-dark text-white rounded-2xl text-sm font-bold shadow-sm hover:bg-brand-primary transition">
                                            Lihat Booking Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-500 text-sm">
                            Belum ada riwayat booking.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection