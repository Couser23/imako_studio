<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA - Imako Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            primary: '#2B5488',
                            dark: '#1A365D',
                            light: '#EBF4FF',
                            accent: '#93C5FD'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #12284C 0%, #1A365D 40%, #2B5488 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-glow {
            position: absolute;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .input-field {
            transition: all 0.2s;
        }
        .input-field:focus {
            border-color: #2B5488;
            box-shadow: 0 0 0 4px rgba(43, 84, 136, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased text-gray-800 flex min-h-screen">

    <!-- KIRI: Visual Area -->
    <div class="hidden lg:flex flex-col w-[40%] hero-gradient p-12 relative text-white justify-between">
        <div class="hero-glow top-0 right-0 translate-x-1/4 -translate-y-1/4"></div>
        <div class="hero-glow bottom-0 left-0 -translate-x-1/4 translate-y-1/4"></div>

        <div class="relative z-10">
            <a href="/" class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 border border-white/20 rounded-xl backdrop-blur-sm hover:bg-white/20 transition cursor-pointer">
                <span class="font-bold text-sm">Kembali</span>
            </a>
        </div>

        <div class="relative z-10 max-w-md">
            <h1 class="text-4xl font-extrabold leading-[1.2] mb-6">
                Keamanan <br/>
                <span class="text-brand-accent">Ekstra.</span>
            </h1>
            <p class="text-white/60 text-sm leading-relaxed">
                Kami melindungi akun Anda dengan autentikasi dua langkah (2FA). Pastikan hanya Anda yang memiliki akses penuh ke sistem studio.
            </p>
        </div>

        <div class="relative z-10 text-white/40 text-xs flex gap-4">
            <span>&copy; 2026 Imako Studio</span>
        </div>
    </div>

    <!-- KANAN: Form Area -->
    <div class="w-full lg:w-[60%] flex flex-col items-center justify-center relative p-6">
        
        <!-- Form Card -->
        <div class="bg-white w-full max-w-[480px] rounded-[32px] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
            <div class="mb-8 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-brand-dark mb-2">Verifikasi 2FA</h2>
                <p class="text-gray-500 text-sm">Buka aplikasi authenticator Anda dan masukkan 6-digit kode verifikasi.</p>
            </div>

            <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-6" autocomplete="off">
                @csrf

                <!-- OTP Code -->
                <div>
                    <input id="verify_code" type="text" name="verify_code" placeholder="••••••" class="w-full h-14 bg-gray-50 border border-gray-200 rounded-xl text-center text-2xl font-bold tracking-[0.5em] input-field @error('verify_code') border-red-500 @enderror" maxlength="6" required autofocus autocomplete="off">
                    @error('verify_code')
                        <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full h-12 bg-brand-dark text-white font-bold rounded-xl hover:bg-[#112440] transition shadow-lg mt-2 text-sm">
                    Verifikasi Kode
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Bukan Anda? 
                    <a href="{{ route('login') }}" class="font-bold text-brand-dark hover:underline">Kembali ke login</a>
                </p>
            </div>
        </div>

        <a href="/" class="absolute bottom-8 flex items-center gap-2 text-gray-400 hover:text-gray-600 transition text-xs font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Kembali ke halaman utama
        </a>

        <!-- Mobile Logo (hanya terlihat di HP) -->
        <a href="/" class="lg:hidden absolute top-8 left-6 flex items-center gap-2">
            <div class="w-8 h-8 bg-brand-primary text-white rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
            </div>
            <span class="font-bold text-brand-dark">Imako Studio</span>
        </a>
    </div>

</body>
</html>
