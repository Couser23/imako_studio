@extends('layouts.admin')

@section('title', 'Profile')
@section('pre-title', 'Pengaturan')
@section('subtitle', 'Kelola informasi studio dan pengaturan sistem')

@section('content')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@endpush

<!-- Header & Subtitle -->
<!-- <div class="mb-6">
    <h2 class="text-sm font-bold text-gray-400">Kelola akun profile Admin Imako Studio</h2>
</div> -->

<!-- Profile Banner & Stats -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8 relative">
    
    <!-- Cover -->
    <div class="h-40 relative @if($user->cover_image) bg-cover bg-center @else bg-blue-600 @endif" @if($user->cover_image) style="background-image: url('{{ asset('images/cover_profile/' . strtolower($user->role ?? 'user') . '/' . $user->cover_image) }}')" @endif>
        <!-- Abstract shapes -->
        <div class="absolute inset-0 opacity-10 overflow-hidden pointer-events-none">
            <div class="w-64 h-64 bg-white rounded-full absolute -top-10 -right-10 blur-3xl"></div>
            <div class="w-64 h-64 bg-white rounded-full absolute -bottom-10 -left-10 blur-3xl"></div>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="absolute top-4 right-4 z-10">
            @csrf
            @method('PATCH')
            <label class="px-3 py-1.5 bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/20 text-white text-[10px] font-extrabold rounded-lg transition flex items-center gap-2 cursor-pointer shadow-sm">
                <input type="file" name="cover_image" class="hidden" onchange="this.form.submit()" accept="image/*">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Ubah cover
            </label>
        </form>
    </div>

    <!-- Profile Info & Avatar -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('avatarUploader', () => ({
                showCropModal: false,
                cropper: null,
                isSaving: false,
                
                selectFile(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.$refs.image.src = e.target.result;
                        this.showCropModal = true;
                        
                        setTimeout(() => {
                            if (this.cropper) {
                                this.cropper.destroy();
                            }
                            if (typeof Cropper === 'undefined') {
                                console.error('Cropper is not loaded!');
                                return;
                            }
                            this.cropper = new Cropper(this.$refs.image, {
                                aspectRatio: 1, // Square avatar
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 1,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        }, 100);
                    };
                    reader.readAsDataURL(file);
                    event.target.value = '';
                },

                resetCropper() {
                    this.showCropModal = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    this.$refs.image.src = '';
                },

                saveAvatar() {
                    if (!this.cropper) return;
                    
                    this.isSaving = true;
                    
                    this.cropper.getCroppedCanvas({
                        width: 400,
                        height: 400,
                    }).toBlob((blob) => {
                        try {
                            const formData = new FormData();
                            formData.append('avatar', blob, 'avatar.jpg');
                            
                            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

                            fetch('{{ route("profile.avatar.update") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': token
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.isSaving = false;
                                this.resetCropper();
                                window.location.reload();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                this.isSaving = false;
                                alert('Terjadi kesalahan saat mengupload foto');
                            });
                        } catch (error) {
                            console.error('Error in saveAvatar:', error);
                            this.isSaving = false;
                            alert('Terjadi kesalahan pada sistem.');
                        }
                    }, 'image/jpeg');
                }
            }));
        });
    </script>
    <div class="px-6 pb-6 pt-20 relative flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6" x-data="avatarUploader">
        
        <!-- Avatar overlapping -->
        <div class="absolute -top-14 left-6">
            <div @click="$refs.fileInput.click()" class="cursor-pointer group w-24 h-24 rounded-2xl {{ $user->avatar ? 'bg-white' : 'bg-brand-dark text-white' }} border-4 border-white shadow-md flex items-center justify-center text-3xl font-black relative overflow-hidden z-10">
                @if($user->avatar)
                    <img src="{{ asset('images/profile_akun/' . strtolower($user->role ?? 'user') . '/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-contain p-1">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            
            <input type="file" x-ref="fileInput" @change="selectFile" class="hidden" accept="image/jpeg, image/png, image/jpg">

            <!-- Crop Modal -->
            <div x-show="showCropModal" x-cloak class="relative z-[100]">
                <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <div @click.away="resetCropper" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-lg p-6">
                            <h3 class="text-sm font-extrabold text-gray-800 mb-4">Sesuaikan Foto Profil</h3>
                            
                            <div class="w-full h-80 bg-gray-100 rounded-xl overflow-hidden mb-6 flex items-center justify-center">
                                <img x-ref="image" src="" alt="Picture" class="max-w-full block">
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="button" @click="resetCropper" class="flex-1 py-3 bg-white border border-gray-300 text-gray-700 font-extrabold text-[11px] rounded-lg hover:bg-gray-50 transition shadow-sm">Batal</button>
                                <button type="button" @click="saveAvatar" :disabled="isSaving" class="flex-1 py-3 bg-brand-dark text-white font-extrabold text-[11px] rounded-lg hover:bg-brand-primary transition shadow-md disabled:opacity-50 flex items-center justify-center gap-2">
                                    <span x-show="isSaving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                    Simpan Foto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div>
            <h3 class="text-xl font-black text-brand-dark mb-1">{{ $user->name }}</h3>
            <p class="text-xs font-bold text-gray-400 mb-3">{{ $user->email }} <span class="mx-1">•</span> {{ $user->phone_number ?? '-' }}</p>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[9px] font-extrabold rounded-full uppercase">{{ $user->role ?? 'User' }}</span>
                <span class="px-2.5 py-1 bg-green-50 text-green-600 text-[9px] font-extrabold rounded-full">Aktif</span>
                <span class="px-2.5 py-1 text-gray-400 text-[9px] font-bold rounded-full">Bergabung {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>

        <!-- Right Stats -->
        <div class="flex items-center gap-6 w-full lg:w-auto relative">
            <!-- <button class="absolute -top-16 right-0 px-4 py-2 bg-brand-dark text-white text-[10px] font-extrabold rounded-lg hover:bg-brand-primary transition flex items-center gap-2 shadow-sm">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Profil
            </button> -->

            @if($user->role === 'admin' && isset($stats))
            <div class="text-center">
                <h4 class="text-xl font-black text-brand-dark leading-none mb-1">{{ $stats['bookings_this_month'] ?? 0 }}</h4>
                <p class="text-[9px] font-bold text-gray-400">Booking bulan ini</p>
            </div>
            <div class="w-px h-8 bg-gray-200"></div>
            <div class="text-center">
                <h4 class="text-xl font-black text-brand-dark leading-none mb-1">{{ $stats['active_employees'] ?? 0 }}</h4>
                <p class="text-[9px] font-bold text-gray-400">Pegawai aktif</p>
            </div>
            <div class="w-px h-8 bg-gray-200"></div>
            <div class="text-center">
                <h4 class="text-xl font-black text-brand-dark leading-none mb-1">Rp {{ number_format($stats['income_this_month'] ?? 0, 0, ',', '.') }}</h4>
                <p class="text-[9px] font-bold text-gray-400">Pemasukan bulan ini</p>
            </div>
            @endif
        </div>

    </div>
</div>

<!-- Main Profile Content Tabs -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    
    <!-- Tabs Nav -->
    <div class="flex overflow-x-auto border-b border-gray-200 hide-scrollbar" id="tab-nav">
        <button onclick="switchTab('profil')" id="btn-tab-profil" class="px-6 py-4 flex items-center gap-2 text-xs font-extrabold text-brand-dark border-b-2 border-brand-dark whitespace-nowrap transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Profil & Akun
        </button>
        <button onclick="switchTab('info')" id="btn-tab-info" class="px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Info Studio
        </button>
        <button onclick="switchTab('notifikasi')" id="btn-tab-notifikasi" class="px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            Notifikasi
        </button>
        <button onclick="switchTab('keamanan')" id="btn-tab-keamanan" class="px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Keamanan
        </button>
        <button onclick="switchTab('aktivitas')" id="btn-tab-aktivitas" class="px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            Aktivitas
        </button>
    </div>

    <!-- Forms Area -->
    <div class="p-8">
        
        @include('admin.profile.partials.tab-profil')
        @include('admin.profile.partials.tab-info')
        @include('admin.profile.partials.tab-notifikasi')
        @include('admin.profile.partials.tab-keamanan')
        @include('admin.profile.partials.tab-aktivitas')
        </div>

        <!-- Zona Berbahaya -->
        <div class="mt-12 bg-red-50/30 rounded-xl border border-red-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-red-200 bg-red-50/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="text-xs font-extrabold text-red-600">Zona Berbahaya</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Reset -->
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <h4 class="text-[11px] font-extrabold text-brand-dark mb-1">Reset semua pengaturan</h4>
                        <p class="text-[9px] font-bold text-gray-400 mb-4 leading-relaxed">Kembalikan semua pengaturan ke nilai bawaan. Data booking tidak terpengaruh.</p>
                    </div>
                    <button type="button" class="w-full py-2 bg-yellow-50 text-yellow-600 border border-yellow-200 hover:bg-yellow-100 text-[10px] font-extrabold rounded-lg transition">
                        Reset Pengaturan
                    </button>
                </div>
                <!-- Export -->
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <h4 class="text-[11px] font-extrabold text-brand-dark mb-1">Export semua data</h4>
                        <p class="text-[9px] font-bold text-gray-400 mb-4 leading-relaxed">Download seluruh data studio (booking, pelanggan, keuangan) dalam format CSV.</p>
                    </div>
                    <button type="button" class="w-full py-2 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 text-[10px] font-extrabold rounded-lg transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export Data
                    </button>
                </div>
                <!-- Delete -->
                <div class="bg-red-50/10 border border-red-200 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <h4 class="text-[11px] font-extrabold text-red-600 mb-1">Hapus akun</h4>
                        <p class="text-[9px] font-bold text-red-400/80 mb-4 leading-relaxed">Tindakan ini permanen dan tidak bisa dibatalkan. Semua data akan dihapus.</p>
                    </div>
                    <button type="button" class="w-full py-2 bg-white text-red-600 border border-red-200 hover:bg-red-50 text-[10px] font-extrabold rounded-lg transition">
                        Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tutup Studio -->
<div id="close-studio-modal" class="fixed inset-0 z-[60] flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeCloseStudioModal()"></div>
    <div id="close-studio-modal-content" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[400px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col">
        
        <!-- Header -->
        <div class="p-6 pb-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-[13px] font-black text-brand-dark tracking-tight">Tutup Studio (Libur)</h3>
            <button onclick="closeCloseStudioModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('admin.studio-settings.close-date') }}" method="POST">
            @csrf
            <!-- Body -->
            <div class="p-6 flex flex-col gap-5 max-h-[60vh] overflow-y-auto">
                
                @if(isset($closedDates) && count($closedDates) > 0)
                <div class="mb-2">
                    <h4 class="text-[10px] font-extrabold text-brand-dark mb-3">Jadwal Libur Terdaftar:</h4>
                    <div class="space-y-2">
                        @foreach($closedDates as $date)
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-[10px] font-bold text-gray-700">{{ \Carbon\Carbon::parse($date->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($date->end_date)->format('d M Y') }}</p>
                                <p class="text-[9px] text-gray-500 font-medium">{{ $date->reason ?? 'Tidak ada keterangan' }}</p>
                            </div>
                            <button type="button" onclick="deleteClosedDate({{ $date->id }})" class="text-red-500 hover:text-red-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-start gap-3">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="text-[10px] font-bold text-gray-500 leading-relaxed">
                        Akan menutup semua paket Studio, sampai dengan waktu yang ditentukan
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brand-dark mb-1.5">Tanggal tutup</label>
                        <div class="relative">
                            <input type="text" name="start_date" id="tanggal_tutup" placeholder="dd/mm/yyyy" class="w-full bg-white border border-gray-200 text-gray-600 text-[11px] font-bold rounded-lg pl-3 pr-8 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm cursor-pointer bg-white" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brand-dark mb-1.5">Buka kembali</label>
                        <div class="relative">
                            <input type="text" name="end_date" id="tanggal_buka" placeholder="dd/mm/yyyy" class="w-full bg-white border border-gray-200 text-gray-600 text-[11px] font-bold rounded-lg pl-3 pr-8 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm cursor-pointer bg-white" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-brand-dark mb-1.5">Berapa hari (Terisi otomatis)</label>
                    <input type="text" id="durasi_libur" value="0 Hari" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-400 text-[11px] font-bold rounded-lg px-3 py-2.5 outline-none shadow-sm cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-brand-dark mb-1.5">Keterangan tutup</label>
                    <input type="text" name="reason" placeholder="Contoh: Hari Raya Idul Fitri" class="w-full bg-white border border-gray-200 text-gray-600 text-[11px] font-bold rounded-lg px-3 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm placeholder-gray-300">
                </div>

            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3 shrink-0 rounded-b-[24px]">
                <button type="button" onclick="closeCloseStudioModal()" class="px-5 py-2 bg-white text-gray-500 hover:text-gray-700 text-[11px] font-extrabold rounded-lg transition flex items-center justify-center">
                    Tutup Modal
                </button>
                <button type="submit" class="px-5 py-2 bg-brand-dark text-white hover:bg-brand-primary text-[11px] font-extrabold rounded-lg transition shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambahkan Libur
                </button>
            </div>
        </form>

        <form id="delete-closed-date-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        // Tab elements
        const tabProfil = document.getElementById('tab-profil');
        const tabInfo = document.getElementById('tab-info');
        const tabNotifikasi = document.getElementById('tab-notifikasi');
        const tabKeamanan = document.getElementById('tab-keamanan');
        const tabAktivitas = document.getElementById('tab-aktivitas');
        
        // Button elements
        const btnProfil = document.getElementById('btn-tab-profil');
        const btnInfo = document.getElementById('btn-tab-info');
        const btnNotifikasi = document.getElementById('btn-tab-notifikasi');
        const btnKeamanan = document.getElementById('btn-tab-keamanan');
        const btnAktivitas = document.getElementById('btn-tab-aktivitas');

        // Hide all
        tabProfil.classList.add('hidden');
        tabInfo.classList.add('hidden');
        if (tabNotifikasi) tabNotifikasi.classList.add('hidden');
        if (tabKeamanan) tabKeamanan.classList.add('hidden');
        if (tabAktivitas) tabAktivitas.classList.add('hidden');

        // Reset button styles
        btnProfil.className = 'px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition';
        btnInfo.className = 'px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition';
        if (btnNotifikasi) btnNotifikasi.className = 'px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition';
        if (btnKeamanan) btnKeamanan.className = 'px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition';
        if (btnAktivitas) btnAktivitas.className = 'px-6 py-4 flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-brand-dark transition border-b-2 border-transparent whitespace-nowrap transition';

        if (tab === 'profil') {
            tabProfil.classList.remove('hidden');
            btnProfil.className = 'px-6 py-4 flex items-center gap-2 text-xs font-extrabold text-brand-dark border-b-2 border-brand-dark whitespace-nowrap transition';
        } else if (tab === 'info') {
            tabInfo.classList.remove('hidden');
            btnInfo.className = 'px-6 py-4 flex items-center gap-2 text-xs font-extrabold text-brand-dark border-b-2 border-brand-dark whitespace-nowrap transition';
        } else if (tab === 'notifikasi') {
            if (tabNotifikasi) tabNotifikasi.classList.remove('hidden');
            if (btnNotifikasi) btnNotifikasi.className = 'px-6 py-4 flex items-center gap-2 text-xs font-extrabold text-brand-dark border-b-2 border-brand-dark whitespace-nowrap transition';
        } else if (tab === 'keamanan') {
            if (tabKeamanan) tabKeamanan.classList.remove('hidden');
            if (btnKeamanan) btnKeamanan.className = 'px-6 py-4 flex items-center gap-2 text-xs font-extrabold text-brand-dark border-b-2 border-brand-dark whitespace-nowrap transition';
        } else if (tab === 'aktivitas') {
            if (tabAktivitas) tabAktivitas.classList.remove('hidden');
            if (btnAktivitas) btnAktivitas.className = 'px-6 py-4 flex items-center gap-2 text-xs font-extrabold text-brand-dark border-b-2 border-brand-dark whitespace-nowrap transition';
        }
    }

    function openCloseStudioModal() {
        const modal = document.getElementById('close-studio-modal');
        const content = document.getElementById('close-studio-modal-content');
        modal.classList.remove('hidden');
        void modal.offsetWidth; // trigger reflow
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'translate-y-4');
        content.classList.add('scale-100', 'translate-y-0');
    }

    function closeCloseStudioModal() {
        const modal = document.getElementById('close-studio-modal');
        const content = document.getElementById('close-studio-modal-content');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-4');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function calculateDays() {
        const startStr = document.getElementById('tanggal_tutup').value; // dd/mm/yyyy
        const endStr = document.getElementById('tanggal_buka').value;

        const durasiInput = document.getElementById('durasi_libur');
        
        if (startStr && endStr) {
            // Convert dd/mm/yyyy to yyyy-mm-dd for Date parsing
            const startParts = startStr.split('/');
            const endParts = endStr.split('/');
            
            if (startParts.length === 3 && endParts.length === 3) {
                const startDate = new Date(startParts[2], startParts[1] - 1, startParts[0]);
                const endDate = new Date(endParts[2], endParts[1] - 1, endParts[0]);
                
                if (endDate >= startDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    durasiInput.value = diffDays + " Hari";
                } else {
                    durasiInput.value = "Tanggal tidak valid";
                }
            }
        } else {
            durasiInput.value = "0 Hari";
        }
    }

    // Auto-switch tab berdasarkan session status setelah form submit
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('status') === 'studio-info-updated')
            switchTab('info');
        @elseif(session('status') === 'password-updated')
            switchTab('keamanan');
        @elseif(session('status') === 'notifikasi-updated')
            switchTab('notifikasi');
        @endif
    });

    function toggleDayUI(label, checkboxId) {
        // Since label is connected to checkbox, the checkbox state changes automatically.
        // We just need to update UI based on the new state after a tiny delay.
        setTimeout(() => {
            const checkbox = document.getElementById(checkboxId);
            if (checkbox.checked) {
                // Ubah jadi aktif
                label.classList.remove('bg-gray-100', 'text-gray-400', 'hover:bg-gray-200');
                label.classList.add('bg-brand-dark', 'text-white', 'shadow-sm');
            } else {
                // Ubah jadi tidak aktif
                label.classList.remove('bg-brand-dark', 'text-white', 'shadow-sm');
                label.classList.add('bg-gray-100', 'text-gray-400', 'hover:bg-gray-200');
            }
        }, 10);
    }
</script>

<!-- Flatpickr CDN for custom Date Picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date pickers for Tutup Studio Modal
        flatpickr("#tanggal_tutup", {
            dateFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                calculateDays();
            }
        });
        
        flatpickr("#tanggal_buka", {
            dateFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                calculateDays();
            }
        });
    });

    function deleteClosedDate(id) {
        if(confirm('Apakah Anda yakin ingin menghapus jadwal libur ini?')) {
            const form = document.getElementById('delete-closed-date-form');
            form.action = '/admin/studio-settings/close-date/' + id;
            form.submit();
        }
    }
</script>

<!-- ClockPicker CDN for Round Analog Clock -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.css">
<style>
    .clockpicker-popover { z-index: 999999 !important; border-radius: 16px; border: none; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15); font-family: 'Plus Jakarta Sans', sans-serif; }
    .clockpicker-popover .popover-title { background-color: #fff; border-radius: 16px 16px 0 0; color: #111827; font-weight: 800; font-size: 14px; padding: 16px; border-bottom: 1px solid #f3f4f6; }
    .text-primary { color: #3b82f6 !important; }
    .clockpicker-tick:hover { background-color: #eff6ff; color: #3b82f6; }
    .clockpicker-canvas-bg { fill: #eff6ff; }
    .clockpicker-canvas-bearing, .clockpicker-canvas-fg { fill: #3b82f6; stroke: #3b82f6; }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.js"></script>
<script>
    function logoutSession(id) {
        if(confirm('Apakah Anda yakin ingin me-logout perangkat ini?')) {
            const form = document.getElementById('logout-specific-session-form');
            form.action = '/profile/sessions/' + id;
            form.submit();
        }
    }

    $(document).ready(function() {
        $('.clockpicker').clockpicker({
            donetext: 'Selesai',
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });

        // Polling for Active Sessions
        setInterval(function() {
            // Only poll if the Keamanan tab is currently active/visible
            if (!$('#tab-keamanan').hasClass('hidden')) {
                $.ajax({
                    url: '{{ route("profile.sessions.html") }}',
                    method: 'GET',
                    success: function(html) {
                        $('#active-sessions-container').html(html);
                        
                        // Toggle the logout all button if sessions <= 1
                        const sessionCount = (html.match(/<div class="flex items-center gap-3 p-3/g) || []).length;
                        if (sessionCount <= 1) {
                            $('#logout-other-devices-btn-container').hide();
                        } else {
                            $('#logout-other-devices-btn-container').show();
                        }
                    }
                });
            }
        }, 3000); // 3 seconds
    });


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@endpush

@endsection
