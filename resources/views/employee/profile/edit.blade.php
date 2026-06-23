@extends('employee.layouts.app', ['title' => 'Profile'])

@section('content')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@endpush

<div class="w-full pb-10 mt-6">
    
    <!-- Profile & Form Card -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden mb-8">
        <!-- Banner -->
        <div class="h-32 w-full relative group bg-gray-200" x-data>
            @if(auth()->user()->cover_image)
                <div class="h-32 bg-cover bg-center absolute inset-0" style="background-image: url('{{ Str::startsWith(auth()->user()->cover_image, ['http://', 'https://']) ? auth()->user()->cover_image : asset('images/cover_profile/pegawai/' . basename(auth()->user()->cover_image)) }}');"></div>
            @else
                <div class="h-32 bg-gradient-to-r from-[#3B82F6] to-[#2563EB] absolute inset-0"></div>
            @endif
            <div class="absolute inset-0 bg-black/10 transition group-hover:bg-black/20"></div>

            <form id="coverForm" method="post" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('patch')
                <input type="file" name="cover_image" x-ref="coverInput" @change="document.getElementById('coverForm').submit()" accept="image/jpeg, image/png, image/jpg">
            </form>
            <button @click="$refs.coverInput.click()" class="absolute top-4 right-4 bg-white/80 hover:bg-white text-gray-800 text-[10px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition flex items-center gap-2 opacity-0 group-hover:opacity-100 backdrop-blur-sm z-10">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Ubah Sampul
            </button>
        </div>
        
        <!-- Profile Info -->
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
        <div class="px-8 relative flex flex-col md:flex-row justify-between items-start" x-data="avatarUploader">
            <div class="flex gap-6 -mt-12">
                <div @click="$refs.fileInput.click()" class="cursor-pointer group w-[90px] h-[90px] rounded-[24px] border-[4px] border-white shadow-sm overflow-hidden relative z-10 bg-[#3B82F6] flex items-center justify-center text-white text-3xl font-extrabold uppercase">
                    @if(auth()->user()->avatar)
                        <img src="{{ Str::startsWith(auth()->user()->avatar, ['http://', 'https://']) ? auth()->user()->avatar : asset('images/profile_akun/pegawai/' . basename(auth()->user()->avatar)) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        {{ collect(explode(' ', auth()->user()->name))->map(fn($s) => substr($s, 0, 1))->take(2)->join('') }}
                    @endif
                    <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                
                <input type="file" x-ref="fileInput" @change="selectFile" class="hidden" accept="image/jpeg, image/png, image/jpg">

                <!-- Crop Modal -->
                <div x-show="showCropModal" x-cloak class="relative z-50">
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
                                    <button type="button" @click="saveAvatar" :disabled="isSaving" class="flex-1 py-3 bg-[#3B82F6] text-white font-extrabold text-[11px] rounded-lg hover:bg-blue-600 transition shadow-md shadow-blue-500/20 disabled:opacity-50 flex items-center justify-center gap-2">
                                        <span x-show="isSaving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                        Simpan Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-14 pb-6">
                    <h2 class="text-xl font-extrabold text-[#1e293b] mb-1">{{ auth()->user()->name }}</h2>
                    <p class="text-[13px] text-gray-400 font-medium mb-3">{{ auth()->user()->email }} &middot; {{ auth()->user()->phone_number ?? '0812-3456-7890' }}</p>
                    <div class="flex items-center gap-2">
                        <span class="bg-[#DBEAFE] text-[#1D4ED8] text-[10px] px-3 py-1 rounded-full font-bold">Fotografer</span>
                    </div>
                </div>
            </div>
            <div class="md:pt-4 absolute right-8 top-32 md:static md:mt-0">
                <span class="bg-[#DCFCE7] text-[#16A34A] text-[11px] px-3 py-1 rounded-full font-bold">Aktif</span>
            </div>
        </div>

        <!-- Form Section -->
        <div class="px-8 pb-8 pt-4 border-t border-gray-50">
            <form action="{{ route('employee.profile.update') }}" method="POST">
                @csrf
                @method('patch')
                
                <h3 class="font-extrabold text-[#1e293b] text-sm mb-5 mt-2">Informasi Pribadi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-2">Nama lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full text-sm rounded-xl border-gray-200 focus:border-[#3B82F6] focus:ring focus:ring-blue-100 py-3 px-4 text-gray-800 shadow-sm transition" placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-2">Nomor HP</label>
                        <input type="text" name="phone_number" value="{{ auth()->user()->phone_number ?? '0812-3456-7890' }}" class="w-full text-sm rounded-xl border-gray-200 focus:border-[#3B82F6] focus:ring focus:ring-blue-100 py-3 px-4 text-gray-800 shadow-sm transition" placeholder="Masukkan nomor HP">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-gray-500 mb-2">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full text-sm rounded-xl border-gray-200 focus:border-[#3B82F6] focus:ring focus:ring-blue-100 py-3 px-4 text-gray-800 shadow-sm transition" placeholder="Masukkan alamat email">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-gray-500 mb-2">Bio singkat</label>
                        <textarea name="bio" rows="2" class="w-full text-sm rounded-xl border-gray-200 focus:border-[#3B82F6] focus:ring focus:ring-blue-100 py-3 px-4 text-gray-800 shadow-sm transition" placeholder="Tulis bio singkat tentang keahlian Anda...">{{ auth()->user()->bio ?? 'Fotografer profesional dengan pengalaman 4 tahun. Spesialisasi: pre-wedding, keluarga, dan studio.' }}</textarea>
                    </div>
                </div>

                <h3 class="font-extrabold text-[#1e293b] text-sm mb-5 mt-8">Ganti Password</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-2">Password saat ini</label>
                        <input type="password" name="current_password" class="w-full text-sm rounded-xl border-gray-200 focus:border-[#3B82F6] focus:ring focus:ring-blue-100 py-3 px-4 text-gray-800 shadow-sm transition" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-2">Password baru</label>
                        <input type="password" name="password" class="w-full text-sm rounded-xl border-gray-200 focus:border-[#3B82F6] focus:ring focus:ring-blue-100 py-3 px-4 text-gray-800 shadow-sm transition" placeholder="Min. 8 karakter">
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-50">
                    <button type="reset" class="px-6 py-2.5 text-xs font-bold text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition bg-white shadow-sm">Reset</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#1F2937] text-white font-bold text-xs rounded-xl hover:bg-gray-800 transition flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Card -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-8 mb-8">
        <h3 class="font-extrabold text-[#1e293b] text-sm mb-6">Statistik Kinerja</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-gray-50/80 rounded-[20px] p-6 text-center">
                <h4 class="text-4xl font-extrabold text-[#1e293b] mb-2">{{ $totalSessions }}</h4>
                <p class="text-xs text-gray-400 font-medium">Total sesi</p>
            </div>
            <div class="bg-gray-50/80 rounded-[20px] p-6 text-center">
                <h4 class="text-4xl font-extrabold text-[#1e293b] mb-2">{{ $sessionsThisMonth }}</h4>
                <p class="text-xs text-gray-400 font-medium">Bulan ini</p>
            </div>
            <div class="bg-gray-50/80 rounded-[20px] p-6 text-center">
                <h4 class="text-[32px] font-extrabold text-[#1e293b] mb-2 leading-tight">{{ $user->created_at->format('M Y') }}</h4>
                <p class="text-xs text-gray-400 font-medium">Bergabung</p>
            </div>
        </div>
        
        <div class="w-full">
            <p class="text-[11px] text-gray-400 mb-4">Sesi per bulan ({{ now()->year }})</p>
            <div class="flex items-end justify-between h-32 gap-1.5 md:gap-3">
                @php $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']; @endphp
                @foreach($months as $index => $month)
                    <div class="flex-1 flex flex-col justify-end items-center gap-3 h-full">
                        @if($chartHeights[$index] > 0)
                            <div class="w-full bg-[#3B82F6] rounded-md transition-all duration-500 hover:bg-[#2563EB]" style="height: {{ $chartHeights[$index] }}%"></div>
                        @else
                            <div class="w-full border-b-2 border-gray-100"></div>
                        @endif
                        <span class="text-[10px] {{ now()->month == $index + 1 ? 'font-bold text-[#3B82F6]' : 'text-gray-400' }}">{{ $month }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Review Card -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-8">
        <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
            <h3 class="font-extrabold text-[#1e293b] text-sm">Ulasan & Rating Klien</h3>
            <div class="bg-[#F0FDF4] text-[#16A34A] px-3 py-1.5 rounded-xl flex items-center gap-1.5 font-bold text-sm shadow-sm">
                <span class="text-yellow-400">⭐</span> {{ number_format($averageRating, 1) }}
            </div>
        </div>
        
        <div class="space-y-4">
            @forelse($reviews as $review)
            <div class="border border-gray-100 rounded-2xl p-6 bg-gray-50/50 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-extrabold text-[#1e293b] mb-1">{{ $review->customer->name ?? 'Klien anonim' }}</h4>
                        <p class="text-[11px] text-gray-400">{{ $review->booking->package->name ?? 'Paket' }} &middot; {{ $review->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="flex text-[#F59E0B] text-sm tracking-widest">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                <span class="text-gray-200">★</span>
                            @endif
                        @endfor
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed">{{ $review->comment }}</p>
            </div>
            @empty
            <div class="text-center py-6">
                <p class="text-sm text-gray-400 font-medium">Belum ada ulasan klien</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@endpush
@endsection
