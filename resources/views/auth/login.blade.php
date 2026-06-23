<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Imako Studio</title>
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
        .input-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
        .input-field {
            padding-left: 2.75rem;
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
    <div class="visual-area hidden lg:flex flex-col w-[40%] hero-gradient p-12 relative text-white justify-between">
        <div class="hero-glow top-0 right-0 translate-x-1/4 -translate-y-1/4"></div>
        <div class="hero-glow bottom-0 left-0 -translate-x-1/4 translate-y-1/4"></div>

        <div class="relative z-10">
            <a href="/" class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 border border-white/20 rounded-xl backdrop-blur-sm hover:bg-white/20 transition cursor-pointer">
                <!-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> -->
                <span class="font-bold text-sm">Kembali Ke Beranda</span>
            </a>
        </div>

        <div class="relative z-10 max-w-md">
            <h1 class="text-4xl font-extrabold leading-[1.2] mb-6">
                Kelola studio foto <br/>
                <span class="text-brand-accent">lebih mudah.</span>
            </h1>
            <p class="text-white/60 text-sm leading-relaxed">
                Booking online, validasi pembayaran, assign fotografer, dan laporan keuangan.<br>Semua dalam satu sistem.
            </p>
        </div>

        <div class="relative z-10 text-white/40 text-xs flex gap-4">
            <span>&copy; 2026 Imako Studio</span>
            <!-- <span>&middot;</span> -->
        </div>
    </div>

    <!-- KANAN: Form Area -->
    <div class="w-full lg:w-[60%] flex flex-col items-center justify-center relative p-6">
        
        <!-- Form Card -->
        <div class="form-card bg-white w-full max-w-[480px] rounded-[32px] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 opacity-0 transform translate-y-8">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-brand-dark mb-2">Selamat datang kembali</h2>
                <p class="text-gray-500 text-sm">Masuk ke akun Imako Studio kamu.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl text-sm input-field @error('email') border-red-500 @enderror" required autofocus>
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-brand-primary hover:underline">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <svg class="w-5 h-5 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input id="password" type="password" name="password" placeholder="••••••••" class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl text-sm input-field pr-10 @error('password') border-red-500 @enderror" required autocomplete="current-password">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary cursor-pointer" name="remember">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="w-full h-12 bg-brand-dark text-white font-bold rounded-xl hover:bg-[#112440] transition shadow-lg mt-2 text-sm">
                    Masuk ke Akun
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="/register" class="font-bold text-brand-dark hover:underline">Daftar gratis</a>
                </p>
            </div>
        </div>

        <a href="/" class="absolute bottom-8 flex items-center gap-2 text-gray-400 hover:text-gray-600 transition text-xs font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Kembali ke halaman utama
        </a>

        <!-- Mobile Logo (hanya terlihat di HP) -->
        <a href="/" class="lg:hidden absolute top-8 left-6 flex items-center gap-2">
            <!-- <div class="w-8 h-8 bg-brand-primary text-white rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
            </div> -->
            <span class="font-bold text-brand-dark">< Kembali Halaman Utama</span>
        </a>
        </a>
    </div>

    <!-- GSAP Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>`;
            } else {
                input.type = "password";
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
            }
        }

        // Animasi bagian visual (kiri)
        gsap.from(".visual-area > div", {
            x: -50,
            opacity: 0,
            duration: 1,
            stagger: 0.15,
            ease: "power3.out"
        });

        // Animasi Form Card (kanan)
        gsap.to(".form-card", {
            y: 0,
            opacity: 1,
            duration: 1,
            delay: 0.3,
            ease: "power3.out"
        });
    </script>
</body>
</html>
