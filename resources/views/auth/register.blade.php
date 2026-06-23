<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Imako Studio</title>
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
        .input-field-no-icon {
            padding-left: 1rem;
            transition: all 0.2s;
        }
        .input-field:focus, .input-field-no-icon:focus {
            border-color: #2B5488;
            box-shadow: 0 0 0 4px rgba(43, 84, 136, 0.1);
            outline: none;
        }
        .glass-feature {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        /* Custom scrollbar to prevent layout shift on Windows */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased text-gray-800 flex min-h-screen">

    <!-- KIRI: Visual Area -->
    <div class="visual-area hidden lg:flex flex-col w-[40%] hero-gradient p-12 relative text-white justify-between sticky top-0 h-screen">
        <div class="hero-glow top-0 right-0 translate-x-1/4 -translate-y-1/4"></div>
        <div class="hero-glow bottom-0 left-0 -translate-x-1/4 translate-y-1/4"></div>

        <div class="relative z-10">
            <a href="/" class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 border border-white/20 rounded-xl backdrop-blur-sm hover:bg-white/20 transition cursor-pointer">
                <!-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> -->
                <span class="font-bold text-sm">Kembali Ke Beranda</span>
            </a>
        </div>

        <div class="relative z-10 max-w-md mt-8">
            <h1 class="text-4xl font-extrabold leading-[1.2] mb-5">
                Bergabung dengan <br/>
                <span class="text-brand-accent">Imako Studio</span>
            </h1>
            <p class="text-white/70 text-sm leading-relaxed mb-10">
                Nikmati kemudahan booking sesi foto dengan layanan profesional dari kami.
            </p>

            <div class="space-y-4">
                <div class="glass-feature p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <span class="text-lg">📅</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Booking Online 24/7</h4>
                        <p class="text-white/60 text-xs">Pilih jadwal kapan saja tanpa ribet</p>
                    </div>
                </div>
                <div class="glass-feature p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <span class="text-lg">📸</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Hasil Foto Berkualitas</h4>
                        <p class="text-white/60 text-xs">Ditangani oleh fotografer profesional</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-white/40 text-xs flex gap-4 mt-8">
            <span>&copy; 2026 Imako Studio</span>
            <span>&middot;</span>
            <a href="#" class="hover:text-white transition">Privasi</a>
            <span>&middot;</span>
            <a href="#" class="hover:text-white transition">Bantuan</a>
        </div>
    </div>

    <!-- KANAN: Form Area -->
    <div class="w-full lg:w-[60%] flex flex-col items-center justify-center relative p-6 min-h-screen py-16 lg:py-10 overflow-y-auto">
        
        <!-- Form Card -->
        <div class="form-card bg-white w-full max-w-[500px] rounded-[32px] p-8 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 my-auto opacity-0 transform translate-y-8">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-brand-dark mb-2">Buat akun baru</h2>
                <p class="text-gray-500 text-sm">Isi data di bawah ini untuk mendaftar</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" autocomplete="off">
                @csrf

                <!-- Nama Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-[11px] font-bold text-gray-700 mb-1.5">Nama depan <span class="text-red-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Rizka" class="w-full h-11 bg-gray-50/50 border border-gray-200 rounded-xl text-sm input-field-no-icon @error('first_name') border-red-500 @enderror" required autofocus>
                        @error('first_name')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-[11px] font-bold text-gray-700 mb-1.5">Nama belakang <span class="text-red-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Amelia" class="w-full h-11 bg-gray-50/50 border border-gray-200 rounded-xl text-sm input-field-no-icon @error('last_name') border-red-500 @enderror" required>
                        @error('last_name')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Nomor HP -->
                <div>
                    <label for="phone_number" class="block text-[11px] font-bold text-gray-700 mb-1.5">Nomor HP <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <svg class="w-4 h-4 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <input id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx" class="w-full h-11 bg-gray-50/50 border border-gray-200 rounded-xl text-sm input-field @error('phone_number') border-red-500 @enderror" required>
                    </div>
                    @error('phone_number')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <svg class="w-4 h-4 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="w-full h-11 bg-gray-50/50 border border-gray-200 rounded-xl text-sm input-field @error('email') border-red-500 @enderror" required>
                    </div>
                    @error('email')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-[11px] font-bold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <div class="relative mb-2">
                        <svg class="w-4 h-4 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input id="password" type="password" name="password" placeholder="Min. 8 karakter" class="w-full h-11 bg-gray-50/50 border border-gray-200 rounded-xl text-sm input-field pr-10 @error('password') border-red-500 @enderror" required>
                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                    <!-- Password Strength -->
                    <!-- <div class="flex gap-1.5">
                        <div class="h-1 w-full bg-gray-200 rounded-full"></div>
                        <div class="h-1 w-full bg-gray-200 rounded-full"></div>
                        <div class="h-1 w-full bg-gray-200 rounded-full"></div>
                        <div class="h-1 w-full bg-gray-200 rounded-full"></div>
                    </div> -->
                </div>

                <!-- T&C Checkbox -->
                <div class="flex items-start gap-2.5 pt-2">
                    <input type="checkbox" id="tnc" class="mt-0.5 w-4 h-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary cursor-pointer" required>
                    <label for="tnc" class="text-xs text-gray-600 cursor-pointer leading-tight">
                        Saya menyetujui <a href="#" class="font-bold text-brand-dark hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="font-bold text-brand-dark hover:underline">Kebijakan Privasi</a>.
                    </label>
                </div>

                <button type="submit" id="submit-btn" class="w-full h-11 bg-brand-dark text-white font-bold rounded-xl hover:bg-[#112440] transition shadow-lg mt-4 text-sm opacity-50 cursor-not-allowed" disabled>
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Sudah punya akun? 
                    <a href="/login" class="font-bold text-brand-dark hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>

        <div class="mt-8">
            <a href="/" class="flex items-center gap-2 text-gray-400 hover:text-gray-600 transition text-xs font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Kembali ke halaman utama
            </a>
        </div>

        <!-- Mobile Logo -->
        <a href="/" class="lg:hidden absolute top-6 left-6 flex items-center gap-2">
            <div class="w-8 h-8 bg-brand-primary text-white rounded-lg flex items-center justify-center">
                <!-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg> -->
            </div>
            <span class="font-bold text-brand-dark">Dashboard</span>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ubah pesan validasi bawaan browser menjadi bahasa Indonesia
            const inputs = document.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('invalid', function(e) {
                    if(e.target.validity.valueMissing) {
                        e.target.setCustomValidity('Harap isi kolom ini terlebih dahulu.');
                    }
                });
                input.addEventListener('input', function(e) {
                    e.target.setCustomValidity('');
                });
            });

            const tncCheckbox = document.getElementById('tnc');
            const submitBtn = document.getElementById('submit-btn');

            function toggleButton() {
                if (tncCheckbox.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            tncCheckbox.addEventListener('change', toggleButton);
            toggleButton(); // Set initial state
        });
    </script>
    
    <!-- GSAP Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>`;
            } else {
                input.type = "password";
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
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
