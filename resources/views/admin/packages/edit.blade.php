@extends('layouts.admin')
@section('hide_topbar', true)

@section('content')
<div class="max-w-6xl mx-auto pt-10 pb-12">
    <!-- Header & Back Button -->
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.packages.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-brand-dark hover:shadow-md transition border border-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-brand-dark tracking-tight">Edit Paket</h2>
            <p class="text-xs font-bold text-gray-400">Edit data paket layanan sistem.</p>
        </div>
    </div>

    <!-- Segmented Control (Pills) -->
    <div class="flex justify-center mb-10">
        <div class="bg-white p-1.5 rounded-2xl inline-flex shadow-sm border border-gray-100 relative">
            <div id="tab-slider" class="absolute top-1.5 bottom-1.5 left-1.5 w-[140px] bg-brand-dark rounded-xl transition-transform duration-300 ease-out z-0"></div>
            
            <button onclick="switchForm('paket')" id="btn-paket" class="relative z-10 w-[140px] py-2.5 text-xs font-extrabold text-white transition-colors duration-300">
                Edit Paket
            </button>
            <button onclick="switchForm('addon')" id="btn-addon" class="relative z-10 w-[150px] py-2.5 text-xs font-extrabold text-gray-500 hover:text-brand-dark transition-colors duration-300">
                Layanan Tambahan
            </button>
        </div>
    </div>

    <!-- Form: Paket -->
    <form id="form-paket" action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Card 1: Informasi Dasar -->
                <div class="bg-white rounded-[24px] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
                    <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Informasi Dasar
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $package->name) }}" required placeholder="Contoh: Wedding Premium Class" class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-extrabold text-gray-700 mb-2">Harga Paket <span class="text-red-500">*</span></label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-0 top-0 bottom-0 px-4 bg-gray-100 border-r border-gray-200 flex items-center rounded-l-xl z-10">
                                        <span class="font-extrabold text-brand-dark text-xs">Rp</span>
                                    </div>
                                    <input type="number" name="price" value="{{ old('price', $package->price) }}" required placeholder="5000000" class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-16 pr-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-gray-700 mb-2">Minimal DP (Opsional)</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-0 top-0 bottom-0 px-4 bg-gray-100 border-r border-gray-200 flex items-center rounded-l-xl z-10">
                                        <span class="font-extrabold text-gray-500 text-xs">Rp</span>
                                    </div>
                                    <input type="text" placeholder="500.000" class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-16 pr-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="4" required placeholder="Jelaskan detail fasilitas paket, misalnya: Akad, Temu, Resepsi, Maksimal 7 jam kerja, 2 Photographer..." class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400 resize-none leading-relaxed">{{ old('description', $package->description) }}</textarea>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                            </label>
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">Status Aktif</h4>
                                <p class="text-[10px] text-gray-400">Jika aktif, paket ini akan muncul di halaman pelanggan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pengaturan Waktu & Ketentuan -->
                <div class="bg-white rounded-[24px] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
                    <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-orange-50 text-orange-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Waktu & Ketentuan Khusus
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Durasi Sesi Pemotretan</label>
                            <div class="relative flex items-center">
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $package->duration_minutes) }}" required placeholder="60" class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-4 pr-16 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                                <div class="absolute right-0 top-0 bottom-0 px-4 bg-gray-100/50 border-l border-gray-200 flex items-center rounded-r-xl z-10">
                                    <span class="font-extrabold text-gray-500 text-[10px] uppercase tracking-wider">Menit</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Jeda Waktu Persiapan (Gap)</label>
                            <div class="relative flex items-center">
                                <input type="number" name="gap_minutes" value="{{ old('gap_minutes', $package->gap_minutes) }}" placeholder="30" class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-4 pr-16 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                                <div class="absolute right-0 top-0 bottom-0 px-4 bg-gray-100/50 border-l border-gray-200 flex items-center rounded-r-xl z-10">
                                    <span class="font-extrabold text-gray-500 text-[10px] uppercase tracking-wider">Menit</span>
                                </div>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 mt-1.5">Waktu istirahat/persiapan alat sebelum sesi berikutnya.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 mb-2">Catatan Tambahan (S&K)</label>
                        <textarea name="terms_and_conditions" rows="2" placeholder="Contoh: Tambahan waktu 200k/jam, Free biaya transport area kota..." class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400 resize-none leading-relaxed">{{ old('terms_and_conditions', $package->terms_and_conditions) }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Right Column: Media & Category -->
            <div class="space-y-8">
                
                <!-- Card 3: Klasifikasi -->
                <div class="bg-white rounded-[24px] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
                    <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-purple-50 text-purple-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        Klasifikasi
                    </h3>
                    
                    <div class="mb-5">
                        <label class="block text-xs font-extrabold text-gray-700 mb-2">Pilih Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition cursor-pointer">
                            <option value="" disabled>Pilih kategori terkait...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $package->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-extrabold text-gray-700 mb-2">Tag Spesial</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tag" value="" class="peer sr-only" {{ old('tag', $package->tag) == '' ? 'checked' : '' }}>
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-center peer-checked:bg-white peer-checked:border-gray-400 peer-checked:ring-2 peer-checked:ring-gray-400/20 transition-all">
                                    <span class="text-xs font-bold text-gray-500 peer-checked:text-gray-800">Tanpa Tag</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tag" value="premium" class="peer sr-only" {{ old('tag', $package->tag) == 'premium' ? 'checked' : '' }}>
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-center peer-checked:bg-gray-900 peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-gray-900/20 transition-all">
                                    <span class="text-xs font-bold text-gray-500 peer-checked:text-amber-300">⭐ Premium</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tag" value="luxury" class="peer sr-only" {{ old('tag', $package->tag) == 'luxury' ? 'checked' : '' }}>
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-center peer-checked:bg-blue-900 peer-checked:border-blue-800 peer-checked:ring-2 peer-checked:ring-blue-900/20 transition-all">
                                    <span class="text-xs font-bold text-gray-500 peer-checked:text-cyan-300">💎 Luxury</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="has_discount" id="has_discount" value="1" class="peer sr-only" {{ old('has_discount', $package->discount_price ? '1' : '') ? 'checked' : '' }}>
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-center peer-checked:bg-red-500 peer-checked:border-red-600 peer-checked:ring-2 peer-checked:ring-red-500/20 transition-all">
                                    <span class="text-xs font-bold text-gray-500 peer-checked:text-white">🏷️ Aktifkan Diskon</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="discount-price-container" class="{{ old('has_discount', $package->discount_price ? '1' : '') ? '' : 'hidden' }}">
                        <label class="block text-xs font-extrabold text-red-500 mb-2">Harga Setelah Diskon (Nett) <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <div class="absolute left-0 top-0 bottom-0 px-4 bg-red-50/50 border-r border-red-200 flex items-center rounded-l-xl z-10">
                                <span class="font-extrabold text-red-500 text-[10px] uppercase tracking-wider">Rp</span>
                            </div>
                            <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price', $package->discount_price ? intval($package->discount_price) : '') }}" placeholder="400000" class="w-full bg-red-50/30 border border-red-200 text-red-700 text-sm font-bold rounded-xl pl-16 pr-4 py-3 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition placeholder-red-300">
                        </div>
                        <p class="text-[9px] font-bold text-red-400 mt-1.5">Harga ini yang akan tampil besar dan harga asli akan dicoret.</p>
                    </div>
                </div>

                <!-- Card 4: Media Upload -->
                <div class="bg-white rounded-[24px] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
                    <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-green-50 text-green-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        Media (Cover)
                    </h3>
                    
                    <div class="relative group cursor-pointer overflow-hidden rounded-2xl">
                        <input type="file" id="image-input" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" accept="image/*">
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center bg-gray-50/50 group-hover:bg-brand-primary/5 group-hover:border-brand-primary/30 transition-all duration-300 text-center relative overflow-hidden">
                            @if($package->image)
                                <img id="image-preview" src="{{ asset('images/paket/' . $package->image) }}" class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:opacity-50 transition-opacity z-0">
                            @else
                                <img id="image-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover opacity-30 group-hover:opacity-50 transition-opacity z-0">
                            @endif
                            <div class="w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center text-brand-primary mb-3 group-hover:scale-110 transition-transform duration-300 z-20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-1 z-20">Pilih Gambar Cover Baru</h5>
                            <p class="text-[10px] font-bold text-gray-400 z-20">Biarkan kosong jika tidak ingin mengubah</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sticky Bottom Action Bar -->
        <div class="mt-8 flex items-center justify-between p-6 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 sticky bottom-6 z-50">
            <button type="reset" class="px-6 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-extrabold rounded-xl transition border border-red-100/50">
                Reset Form
            </button>
            <button type="submit" class="px-8 py-3 bg-brand-dark text-white hover:bg-brand-primary text-sm font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Paket
            </button>
        </div>
    </form>

    <!-- Form: Add-on (Layanan Tambahan) -->
    <form id="form-addon" action="{{ route('admin.addons.store') }}" method="POST" class="hidden relative max-w-4xl mx-auto">
        @csrf
        <div class="bg-white rounded-[24px] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
            <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                Tambah Layanan (Add-on) Sekaligus
            </h3>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 mb-2">Terhubung ke Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full bg-gray-50/50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition cursor-pointer">
                        <option value="" disabled selected>Pilih Kategori...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="addon-rows" class="space-y-4">
                    <!-- Row 1 -->
                    <div class="addon-row flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Nama Tambahan <span class="text-red-500">*</span></label>
                            <input type="text" name="addons[0][name]" required placeholder="Contoh: Tambah Orang" class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute left-0 top-0 bottom-0 px-4 bg-gray-100 border-r border-gray-200 flex items-center rounded-l-xl z-10">
                                    <span class="font-extrabold text-brand-dark text-xs">Rp</span>
                                </div>
                                <input type="number" name="addons[0][price]" required placeholder="15000" class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-16 pr-4 py-3 focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                            </div>
                        </div>
                        <div class="w-1/4">
                            <label class="block text-xs font-extrabold text-gray-700 mb-2">Ekstra Waktu (Opsional)</label>
                            <div class="relative flex items-center">
                                <input type="number" name="addons[0][extra_minutes]" placeholder="0" class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-4 pr-12 py-3 focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                                <div class="absolute right-0 top-0 bottom-0 px-3 bg-gray-100/50 border-l border-gray-200 flex items-center rounded-r-xl z-10">
                                    <span class="font-extrabold text-gray-500 text-[10px] uppercase">Mnt</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="removeAddonRow(this)" class="mt-7 w-12 h-[46px] shrink-0 flex items-center justify-center bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="button" onclick="addAddonRow()" class="px-6 py-2.5 border-2 border-dashed border-brand-primary/30 text-brand-primary hover:bg-brand-primary/5 text-xs font-extrabold rounded-xl transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Baris Lagi
                    </button>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-brand-dark text-white hover:bg-brand-primary text-sm font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Semua Tambahan
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>

@push('scripts')
<script>
    function switchForm(type) {
        const formPaket = document.getElementById('form-paket');
        const formAddon = document.getElementById('form-addon');
        
        const btnPaket = document.getElementById('btn-paket');
        const btnAddon = document.getElementById('btn-addon');
        
        const tabSlider = document.getElementById('tab-slider');

        formPaket.classList.add('hidden');
        formAddon.classList.add('hidden');

        btnPaket.classList.replace('text-white', 'text-gray-500');
        btnAddon.classList.replace('text-white', 'text-gray-500');

        if (type === 'paket') {
            formPaket.classList.remove('hidden');
            tabSlider.style.transform = 'translateX(0)';
            tabSlider.style.width = '140px';
            btnPaket.classList.replace('text-gray-500', 'text-white');
        } else if (type === 'addon') {
            formAddon.classList.remove('hidden');
            tabSlider.style.transform = 'translateX(140px)';
            tabSlider.style.width = '150px';
            btnAddon.classList.replace('text-gray-500', 'text-white');
        }
    }

    // Image Preview
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('image-preview');
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Tag Diskon Logic
    const discountCheckbox = document.getElementById('has_discount');
    const discountContainer = document.getElementById('discount-price-container');
    const discountInput = document.getElementById('discount_price');

    discountCheckbox.addEventListener('change', function() {
        if (this.checked) {
            discountContainer.classList.remove('hidden');
            discountInput.required = true;
        } else {
            discountContainer.classList.add('hidden');
            discountInput.required = false;
            discountInput.value = '';
        }
    });

    let addonRowIndex = 0;

    function addAddonRow() {
        addonRowIndex++;
        const container = document.getElementById('addon-rows');
        const newRow = document.createElement('div');
        newRow.className = 'addon-row flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100';
        newRow.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-extrabold text-gray-700 mb-2">Nama Tambahan <span class="text-red-500">*</span></label>
                <input type="text" name="addons[${addonRowIndex}][name]" required placeholder="Contoh: Tambah Orang" class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-extrabold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-0 top-0 bottom-0 px-4 bg-gray-100 border-r border-gray-200 flex items-center rounded-l-xl z-10">
                        <span class="font-extrabold text-brand-dark text-xs">Rp</span>
                    </div>
                    <input type="number" name="addons[${addonRowIndex}][price]" required placeholder="15000" class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-16 pr-4 py-3 focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                </div>
            </div>
            <div class="w-1/4">
                <label class="block text-xs font-extrabold text-gray-700 mb-2">Ekstra Waktu</label>
                <div class="relative flex items-center">
                    <input type="number" name="addons[${addonRowIndex}][extra_minutes]" placeholder="0" class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-bold rounded-xl pl-4 pr-12 py-3 focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                    <div class="absolute right-0 top-0 bottom-0 px-3 bg-gray-100/50 border-l border-gray-200 flex items-center rounded-r-xl z-10">
                        <span class="font-extrabold text-gray-500 text-[10px] uppercase">Mnt</span>
                    </div>
                </div>
            </div>
            <button type="button" onclick="removeAddonRow(this)" class="mt-7 w-12 h-[46px] shrink-0 flex items-center justify-center bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(newRow);
    }

    function removeAddonRow(btn) {
        const rows = document.querySelectorAll('.addon-row');
        if (rows.length > 1) {
            btn.closest('.addon-row').remove();
        } else {
            alert('Minimal harus ada 1 baris tambahan.');
        }
    }
</script>
@endpush
@endsection
