<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - Imako Studio</title>
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
    <div class="hidden lg:flex flex-col w-[40%] hero-gradient p-12 relative text-white justify-between">
        <div class="hero-glow top-0 right-0 translate-x-1/4 -translate-y-1/4"></div>
        <div class="hero-glow bottom-0 left-0 -translate-x-1/4 translate-y-1/4"></div>

        <div class="relative z-10">
            <a href="/" class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 border border-white/20 rounded-xl backdrop-blur-sm hover:bg-white/20 transition cursor-pointer">
                <span class="font-bold text-sm">Beranda</span>
            </a>
        </div>

        <div class="relative z-10 max-w-md">
            <h1 class="text-4xl font-extrabold leading-[1.2] mb-6">
                Buat password <br/>
                <span class="text-brand-accent">baru Anda.</span>
            </h1>
            <p class="text-white/60 text-sm leading-relaxed">
                Silakan buat password baru yang kuat dan mudah diingat. Pastikan password Anda aman!
            </p>
        </div>

        <div class="relative z-10 text-white/40 text-xs flex gap-4">
            <span>&copy; 2026 Imako Studio</span>
            <span>&middot;</span>
            <a href="#" class="hover:text-white transition">Privasi</a>
            <span>&middot;</span>
            <a href="#" class="hover:text-white transition">Bantuan</a>
        </div>
    </div>

    <!-- KANAN: Form Area -->
    <div class="w-full lg:w-[60%] flex flex-col items-center justify-center relative p-6">
        
        <!-- Form Card -->
        <div class="bg-white w-full max-w-[480px] rounded-[32px] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-brand-dark mb-2">Atur Ulang Password 🔑</h2>
                <p class="text-gray-500 text-sm">Buat password baru untuk akun Anda.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="email@contoh.com" class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl text-sm input-field @error('email') border-red-500 @enderror" required autofocus autocomplete="username" readonly>
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <svg class="w-5 h-5 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input id="password" type="password" name="password" placeholder="••••••••" class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl text-sm input-field pr-10 @error('password') border-red-500 @enderror" required autocomplete="new-password">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <svg class="w-5 h-5 input-icon left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl text-sm input-field pr-10 @error('password_confirmation') border-red-500 @enderror" required autocomplete="new-password">
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full h-12 bg-brand-dark text-white font-bold rounded-xl hover:bg-[#112440] transition shadow-lg mt-2 text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Password Baru
                </button>
            </form>
        </div>

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
