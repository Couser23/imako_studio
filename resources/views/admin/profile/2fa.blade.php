@php
    $layout = 'layouts.app';
    if(auth()->check() && auth()->user()->role === 'admin') $layout = 'layouts.admin';
    if(auth()->check() && auth()->user()->role === 'pegawai') $layout = 'employee.layouts.app';
    if(auth()->check() && auth()->user()->role === 'user') $layout = 'layouts.user';
@endphp
@extends($layout)

@section('title', 'Keamanan 2FA')
@section('pre-title', 'Pengaturan')
@section('subtitle', 'Konfigurasi Autentikasi 2 Langkah')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8 p-8">
    <div class="max-w-xl mx-auto">
        <h2 class="text-lg font-bold text-brand-dark mb-4 text-center">Konfigurasi Autentikasi 2 Langkah (2FA)</h2>
        <p class="text-xs text-gray-500 mb-8 text-center leading-relaxed">
            Pindai QR Code di bawah menggunakan aplikasi Authenticator pilihan Anda (seperti Google Authenticator, Authy, atau Microsoft Authenticator), lalu masukkan kode yang dihasilkan untuk mengaktifkan 2FA.
        </p>

        <div class="flex justify-center mb-8">
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm inline-block">
                {!! $QR_Image !!}
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-8">
            <p class="text-[11px] font-bold text-blue-600 text-center mb-2">Tidak bisa memindai QR Code?</p>
            <p class="text-xs font-mono font-bold text-brand-dark text-center tracking-wider bg-white py-2 rounded border border-blue-100">
                {{ $secret }}
            </p>
        </div>

        <form action="{{ route('2fa.enable') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-[11px] font-bold text-gray-500 mb-2 text-center">Masukkan 6-digit kode OTP dari aplikasi Anda</label>
                <input type="text" name="verify_code" class="w-full text-center bg-gray-50 border border-gray-200 text-brand-dark text-lg font-bold tracking-[0.5em] rounded-lg px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm" placeholder="••••••" maxlength="6" required autofocus>
                @error('verify_code')
                    <p class="text-[10px] font-bold text-red-500 mt-2 text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center gap-3">
                @if(request()->routeIs('profile.edit'))
                    <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-500 text-[11px] font-extrabold rounded-lg hover:bg-gray-50 transition shadow-sm">Batal</a>
                @else
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-white border border-red-200 text-red-500 text-[11px] font-extrabold rounded-lg hover:bg-red-50 transition shadow-sm">Logout</button>
                    </form>
                @endif
                <button type="submit" class="px-5 py-2.5 bg-brand-dark text-white text-[11px] font-extrabold rounded-lg hover:bg-brand-primary transition shadow-sm">Aktifkan 2FA</button>
            </div>
        </form>
    </div>
</div>
@endsection
