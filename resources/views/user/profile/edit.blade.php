@extends('layouts.user', ['title' => 'Profile'])

@section('content')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@endpush
<div class="mb-6 -mt-4">
    <p class="text-sm text-gray-400 font-medium">Kelola data akun dan informasi pribadi Anda</p>
</div>

<div class="max-w-3xl mx-auto flex flex-col gap-8 pb-10">
    
    <!-- Status Messages -->
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('status') === 'profile-updated' ? 'Profil berhasil diperbarui.' : 'Password berhasil diubah.' }}
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <!-- User Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Cover Image -->
        <div class="h-36 w-full bg-gray-200 relative group" x-data>
            @if(Auth::user()->cover_image)
                <img src="{{ asset('images/cover_profile/' . strtolower(Auth::user()->role ?? 'user') . '/' . Auth::user()->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('images/family_06.jpg') }}" alt="Cover" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-black/10"></div>
            
            <form id="coverForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="hidden">
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
        <div class="px-8 pb-8 relative" x-data="avatarUploader()">
            <!-- Avatar -->
            <div @click="$refs.fileInput.click()" class="cursor-pointer group w-20 h-20 rounded-full border-4 border-white bg-[#E8F0FE] text-brand-blue flex items-center justify-center text-3xl font-black absolute -top-10 left-8 shadow-sm overflow-hidden z-10">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('images/profile_akun/' . strtolower(Auth::user()->role ?? 'user') . '/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
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
                                <button type="button" @click="saveAvatar" :disabled="isSaving" class="flex-1 py-3 bg-[#1E40AF] text-white font-extrabold text-[11px] rounded-lg hover:bg-blue-900 transition shadow-md shadow-blue-500/20 disabled:opacity-50 flex items-center justify-center gap-2">
                                    <span x-show="isSaving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                    Simpan Foto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end pt-4 mb-2">
                <span class="px-4 py-1 bg-emerald-50 text-emerald-500 rounded-full text-[10px] font-extrabold border border-emerald-100">Aktif</span>
            </div>
            
            <div class="mt-2">
                <h2 class="text-xl font-extrabold text-brand-dark mb-0.5">{{ Auth::user()->name }}</h2>
                <p class="text-xs text-gray-400 font-medium">{{ Auth::user()->email }} • {{ Auth::user()->phone_number ?? 'Belum ada nomor HP' }}</p>
            </div>
            
            @php
                $totalBooking = Auth::user()->bookings()->count();
                $totalBayar = Auth::user()->payments()->where('payments.status', 'verified')->sum('payments.amount');
                
                $formattedAmount = 'Rp 0';
                if ($totalBayar >= 1000000) {
                    $formattedAmount = 'Rp ' . rtrim(rtrim(number_format($totalBayar / 1000000, 1, ',', '.'), '0'), ',') . 'jt';
                } elseif ($totalBayar > 0) {
                    $formattedAmount = 'Rp ' . number_format($totalBayar, 0, ',', '.');
                }
            @endphp
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t border-gray-100">
                <div class="text-center">
                    <p class="text-lg font-black text-brand-dark">{{ $totalBooking }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Total booking</p>
                </div>
                <div class="text-center border-l border-r border-gray-100">
                    <p class="text-lg font-black text-emerald-500">{{ $formattedAmount }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Total bayar</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-black text-brand-dark">{{ Auth::user()->created_at->translatedFormat('M Y') }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Bergabung</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div x-data="{ activeTab: '{{ $errors->updatePassword->isNotEmpty() ? 'password' : 'profile' }}' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Tabs Header -->
        <div class="flex items-center border-b border-gray-100 bg-gray-50/50">
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'border-b-2 border-brand-blue text-brand-blue bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="flex-1 py-4 text-[13px] font-extrabold transition">Edit Profil</button>
            <button @click="activeTab = 'password'" :class="activeTab === 'password' ? 'border-b-2 border-brand-blue text-brand-blue bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="flex-1 py-4 text-[13px] font-extrabold transition">Ubah Password</button>
        </div>

        <!-- Profile Tab -->
        <div x-show="activeTab === 'profile'" class="p-8">
            <h3 class="font-extrabold text-brand-dark mb-8">Informasi Pribadi</h3>
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-[11px] font-extrabold text-gray-700 mb-2 uppercase tracking-wide">Nama lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition" required>
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone_number" class="block text-[11px] font-extrabold text-gray-700 mb-2 uppercase tracking-wide">Nomor HP</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" placeholder="Masukan nomor telepon Anda" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>
                    
                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-[11px] font-extrabold text-gray-700 mb-2 uppercase tracking-wide">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition" required>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-50">
                    <button type="reset" class="px-8 py-3 border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition">Reset</button>
                    <button type="submit" class="px-8 py-3 bg-brand-dark text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-sm">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Password Tab -->
        <div x-show="activeTab === 'password'" x-cloak class="p-8">
            <h3 class="font-extrabold text-brand-dark mb-8">Ubah Password Akun</h3>
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div class="md:col-span-2">
                        <label for="current_password" class="block text-[11px] font-extrabold text-gray-700 mb-2 uppercase tracking-wide">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition" required>
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                    </div>
                    
                    <div>
                        <label for="password" class="block text-[11px] font-extrabold text-gray-700 mb-2 uppercase tracking-wide">Password Baru</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition" required>
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-extrabold text-gray-700 mb-2 uppercase tracking-wide">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition" required>
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-50">
                    <button type="reset" class="px-8 py-3 border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-brand-dark text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-sm">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    function avatarUploader() {
        return {
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
                    
                    // Initialize cropper after image is visible
                    setTimeout(() => {
                        if (this.cropper) {
                            this.cropper.destroy();
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
                // clear input
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
                    const formData = new FormData();
                    formData.append('avatar', blob, 'avatar.jpg');
                    
                    fetch('{{ route("profile.avatar.update") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                }, 'image/jpeg');
            }
        }
    }
</script>
@endpush
@endsection
