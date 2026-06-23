@extends('layouts.admin')
@section('hide_topbar', true)

@section('content')
<div class="w-full pt-6 pb-12">
    <!-- Header & Back Button -->
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.payment-methods.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-brand-dark hover:shadow-md transition border border-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-brand-dark tracking-tight">Edit Metode Pembayaran</h2>
            <p class="text-xs font-bold text-gray-400">Edit jenis pembayaran yang sudah ada.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden">
        
        <!-- Form Section -->
        <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf
            @method('PUT')
            
            <!-- Type Selector -->
            <div class="mb-10">
                <label class="block text-sm font-extrabold text-brand-dark mb-4">Pilih Jenis Pembayaran</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- Type 1: Transfer -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="provider" value="transfer" class="peer sr-only" {{ old('provider', $paymentMethod->provider) == 'transfer' ? 'checked' : '' }} onchange="toggleForm()">
                        <div class="bg-white border-2 border-gray-100 rounded-2xl p-5 hover:border-blue-100 transition-all peer-checked:border-brand-primary peer-checked:bg-blue-50/50 peer-checked:shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mb-3 peer-checked:bg-brand-primary peer-checked:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h4 class="text-sm font-extrabold text-brand-dark mb-1">Transfer Bank / E-Wallet</h4>
                            <p class="text-[10px] font-bold text-gray-400">BCA, Mandiri, BRI, Gopay, OVO, Dana dll.</p>
                        </div>
                    </label>

                    <!-- Type 2: QRIS / E-Wallet -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="provider" value="qris" class="peer sr-only" {{ old('provider', $paymentMethod->provider) == 'qris' ? 'checked' : '' }} onchange="toggleForm()">
                        <div class="bg-white border-2 border-gray-100 rounded-2xl p-5 hover:border-blue-100 transition-all peer-checked:border-brand-primary peer-checked:bg-blue-50/50 peer-checked:shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mb-3 peer-checked:bg-brand-primary peer-checked:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <h4 class="text-sm font-extrabold text-brand-dark mb-1">QRIS</h4>
                            <p class="text-[10px] font-bold text-gray-400">Gunakan metode ini untuk pembayaran via QRIS.</p>
                        </div>
                    </label>

                    <!-- Type 3: Cash -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="provider" value="cash" class="peer sr-only" {{ old('provider', $paymentMethod->provider) == 'cash' ? 'checked' : '' }} onchange="toggleForm()">
                        <div class="bg-white border-2 border-gray-100 rounded-2xl p-5 hover:border-blue-100 transition-all peer-checked:border-brand-primary peer-checked:bg-blue-50/50 peer-checked:shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mb-3 peer-checked:bg-brand-primary peer-checked:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h4 class="text-sm font-extrabold text-brand-dark mb-1">Cash (Tunai)</h4>
                            <p class="text-[10px] font-bold text-gray-400">Bayar di studio.</p>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Dynamic Form Fields -->
            <div class="space-y-6">
                
                <!-- Field: Nama Bank / E-Wallet -->
                <div id="field-provider" class="transition-all duration-300">
                    <label class="block text-xs font-extrabold text-brand-dark mb-2">Nama Bank / Provider <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" placeholder="Contoh: BCA, Mandiri, Gopay..." class="w-full bg-gray-50/50 border border-gray-200 text-brand-dark text-sm font-bold rounded-xl px-4 py-3.5 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                </div>

                <!-- Field: Nomor Rekening -->
                <div id="field-rekening" class="transition-all duration-300">
                    <label class="block text-xs font-extrabold text-brand-dark mb-2">Nomor Rekening / HP <span class="text-red-500">*</span></label>
                    <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number != '-' ? $paymentMethod->account_number : '') }}" placeholder="Contoh: 3674764838" class="w-full bg-gray-50/50 border border-gray-200 text-brand-dark text-sm font-bold rounded-xl px-4 py-3.5 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                </div>

                <!-- Field: Atas Nama -->
                <div id="field-atas-nama" class="transition-all duration-300">
                    <label class="block text-xs font-extrabold text-brand-dark mb-2">Atas Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="account_name" value="{{ old('account_name', $paymentMethod->account_name != '-' ? $paymentMethod->account_name : '') }}" placeholder="Contoh: Imako Studio" class="w-full bg-gray-50/50 border border-gray-200 text-brand-dark text-sm font-bold rounded-xl px-4 py-3.5 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400">
                </div>

                <!-- Field: Upload QR -->
                <div id="field-qr" class="transition-all duration-300">
                    <label class="block text-xs font-extrabold text-brand-dark mb-2">Upload QR Code <span class="text-gray-400 font-medium">(Wajib untuk QRIS/E-Wallet)</span></label>
                    <label class="w-full bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center py-8 cursor-pointer hover:bg-blue-50/30 hover:border-blue-200 transition group relative overflow-hidden">
                        <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, application/pdf">
                        @if($paymentMethod->logo)
                            <img src="{{ asset('images/metode_pembayaran/' . $paymentMethod->logo) }}" class="absolute inset-0 w-full h-full object-contain opacity-30 group-hover:opacity-50 transition-opacity">
                        @endif
                        <div class="w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center text-gray-300 mb-3 group-hover:scale-110 group-hover:text-brand-primary transition-transform duration-300 z-20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <h4 class="text-xs font-extrabold text-brand-dark mb-1 z-20">Pilih Gambar QR Baru</h4>
                        <p class="text-[10px] font-bold text-gray-400 text-center max-w-[250px] z-20">Biarkan kosong jika tidak ingin mengubah</p>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                    </label>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Status Aktif</h4>
                        <p class="text-[10px] text-gray-400">Jika aktif, metode ini akan muncul di halaman pelanggan.</p>
                    </div>
                </div>

                <!-- Field: Keterangan (For Cash) -->
                <div id="field-keterangan" class="hidden transition-all duration-300">
                    <label class="block text-xs font-extrabold text-brand-dark mb-2">Keterangan Tambahan</label>
                    <textarea rows="4" placeholder="Misal: Pembayaran dilakukan langsung di meja kasir studio sebelum/sesudah sesi foto." class="w-full bg-gray-50/50 border border-gray-200 text-brand-dark text-sm font-bold rounded-xl px-4 py-3.5 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition placeholder-gray-400 resize-none"></textarea>
                </div>

            </div>
        </div>

        <!-- Sticky Bottom Bar Actions -->
        <div class="bg-gray-50/80 backdrop-blur-sm border-t border-gray-100 p-6 flex justify-end gap-3 sticky bottom-0 z-20">
            <a href="{{ route('admin.payment-methods.index') }}" class="px-8 py-3 bg-white text-gray-500 hover:text-brand-dark hover:bg-gray-100 text-sm font-extrabold rounded-xl transition border border-gray-200 shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-brand-dark text-white hover:bg-brand-primary text-sm font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)]">
                Simpan Metode
            </button>
        </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleForm() {
        const type = document.querySelector('input[name="provider"]:checked').value;
        
        const fProvider = document.getElementById('field-provider');
        const fRekening = document.getElementById('field-rekening');
        const fAtasNama = document.getElementById('field-atas-nama');
        const fQr = document.getElementById('field-qr');
        const fKeterangan = document.getElementById('field-keterangan');

        // Reset display
        fProvider.classList.remove('hidden');
        fRekening.classList.remove('hidden');
        fAtasNama.classList.remove('hidden');
        fQr.classList.remove('hidden');
        fKeterangan.classList.remove('hidden');

        if (type === 'transfer') {
            fQr.classList.add('hidden');
            fKeterangan.classList.add('hidden');
        } else if (type === 'qris') {
            fRekening.classList.add('hidden');
            fKeterangan.classList.add('hidden');
        } else if (type === 'cash') {
            fProvider.classList.add('hidden');
            fRekening.classList.add('hidden');
            fAtasNama.classList.add('hidden');
            fQr.classList.add('hidden');
            // Hanya keterangan yang tampil
        }
    }

    // Init form state on load
    document.addEventListener('DOMContentLoaded', () => {
        toggleForm();
    });
</script>
@endpush
@endsection
