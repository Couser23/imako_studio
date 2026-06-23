<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imako Studio - Abadikan Momen Terbaik Bersama Kami</title>
    <!-- MENGGUNAKAN CDN AGAR TIDAK ADA LAGI MASALAH CACHE/VITE -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            primary: '#2B5488',
                            dark: '#1A365D',
                            light: '#EBF4FF'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAFA; overflow-x: hidden; }
        .hero-gradient {
            background: linear-gradient(135deg, #1A365D 0%, #2B5488 50%, #4299E1 100%);
            position: relative;
        }
        .hero-glow {
            position: absolute;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .text-gradient {
            background: linear-gradient(90deg, #93C5FD, #E0E7FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .wave-bottom {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        .wave-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 70px;
        }
        .wave-bottom .shape-fill { fill: #FAFAFA; }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-floating {
            animation: floating 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <!-- NAVBAR -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2">
                    <!-- <div class="w-10 h-10 bg-brand-primary text-white rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div> -->
                    <img src="{{ asset('images/(watermark) logo imako grey.png') }}" alt="Logo Imako Studio" class="h-16 w-auto">
                    <!-- <span class="font-bold text-xl text-brand-dark tracking-tight">Imako Studio</span> -->
                    
                </a>
                <!-- Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#layanan" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">Layanan</a>
                    <a href="#paket" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">Paket</a>
                    <a href="#portofolio" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">Portofolio</a>
                    <a href="#cara-booking" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">Cara Booking</a>
                    <a href="#testimoni" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">Testimoni</a>
                    <a href="#faq" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">FAQ</a>
                    <a href="#kontak" class="text-gray-600 hover:text-brand-primary text-sm font-semibold transition">Kontak</a>
                </div>
                <!-- Auth -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="/login" class="px-5 py-2.5 border border-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-50 transition text-sm">Masuk</a>
                    <a href="/register" class="px-5 py-2.5 bg-brand-primary text-white font-bold rounded-full hover:bg-brand-dark transition shadow-lg shadow-brand-primary/20 text-sm">Daftar Gratis</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-brand-primary focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-b border-gray-100 shadow-xl" style="display: none;">
            <div class="px-4 py-5 space-y-3 flex flex-col">
                <a href="#layanan" @click="mobileMenuOpen = false" class="text-gray-600 font-bold hover:text-brand-primary">Layanan</a>
                <a href="#paket" @click="mobileMenuOpen = false" class="text-gray-600 font-bold hover:text-brand-primary">Paket</a>
                <a href="#portofolio" @click="mobileMenuOpen = false" class="text-gray-600 font-bold hover:text-brand-primary">Portofolio</a>
                <hr class="border-gray-100 my-2">
                <a href="/login" class="w-full text-center px-5 py-3 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition text-sm">Masuk</a>
                <a href="/register" class="w-full text-center px-5 py-3 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-dark transition shadow-lg text-sm">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <div class="hero-gradient pt-32 pb-44 relative overflow-hidden">
        <div class="hero-glow top-0 right-0 transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="hero-glow bottom-0 left-0 transform -translate-x-1/2 translate-y-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Kiri -->
                <div class="hero-content">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm mb-6">
                        @if($isOpen)
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            <span class="text-white text-xs font-semibold tracking-wide">Buka {{ $settings ? substr($settings->open_time, 0, 5) . '-' . substr($settings->close_time, 0, 5) : '09.00-18.00' }} WIB</span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-white text-xs font-semibold tracking-wide">{{ $closeReason }}</span>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-[54px] font-extrabold text-white leading-tight mb-6 tracking-tight">
                        Abadikan <span class="text-gradient">Momen Terbaik</span><br/>Bersama Kami
                    </h1>
                    <p class="text-white/80 text-lg mb-8 max-w-xl leading-relaxed">
                        Studio foto profesional di Malang. Booking mudah secara online, fotografer berpengalaman, dan hasil foto langsung dikirim ke akun kamu.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mb-12">
                        <a href="/login" class="px-8 py-3.5 bg-white text-brand-dark font-bold rounded-xl hover:bg-gray-100 transition shadow-lg text-sm">Pilih Booking Sekarang</a>
                        <a href="#paket" class="px-8 py-3.5 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition backdrop-blur-sm border border-white/20 text-sm">Lihat Paket &gt;</a>
                    </div>
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t border-white/10">
                        <div><h4 class="text-2xl font-black text-white">{{ \App\Models\Booking::where('status', 'completed')->count() }}+</h4><p class="text-white/60 text-[11px] uppercase tracking-wider mt-1">Klien puas</p></div>
                        <div><h4 class="text-2xl font-black text-white">{{ \App\Models\Package::count() }}</h4><p class="text-white/60 text-[11px] uppercase tracking-wider mt-1">Jenis paket</p></div>
                        <div><h4 class="text-2xl font-black text-white">4+</h4><p class="text-white/60 text-[11px] uppercase tracking-wider mt-1">Tahun pengalaman</p></div>
                        <div><h4 class="text-2xl font-black text-white flex items-center gap-1"><span class="text-yellow-400">⭐</span> {{ number_format(\App\Models\Review::avg('rating') ?? 5, 1) }}</h4><p class="text-white/60 text-[11px] uppercase tracking-wider mt-1">Rating</p></div>
                    </div>
                </div>
                
                <!-- Kanan -->
                <div class="hero-panel relative lg:ml-auto w-full max-w-sm mt-12 lg:mt-0 mx-auto">
                    <div class="animate-floating">
                        <!-- Floating badge -->
                        <div class="absolute -top-4 -right-4 bg-red-500 z-20 px-4 py-1.5 rounded-full flex items-center gap-2 shadow-lg">
                            <span class="text-white text-[10px] font-black uppercase tracking-wider">Hot Sale!</span>
                        </div>

                        <div class="glass-panel rounded-3xl p-6 relative z-10 shadow-2xl" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.2">
                        <div class="flex flex-col items-center pb-5 border-b border-white/20 mb-5 text-center">
                            <!-- <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            </div> -->
                            <img src="{{ asset('images/(watermark) logo imako white new.png') }}" alt="Logo Imako Studio" class="h-8 w-auto">
                            <!-- <h3 class="text-white font-bold text-lg">Imako Studio</h3> -->
                            <p class="text-white/60 text-xs mt-1">Daftar Harga Paket Layanan</p>
                        </div>
                        <div class="space-y-3">
                            @foreach($hotSalePackages as $index => $package)
                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-white/10 hover:bg-white/15 transition border border-white/10">
                                <div class="flex items-center gap-3">
                                    <span class="text-white text-sm font-bold">{{ $package->name }}</span>
                                </div>
                                <span class="text-white/80 text-xs font-mono font-bold bg-white/10 px-2 py-1 rounded">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        <a href="/login" class="mt-6 w-full py-3.5 bg-white text-brand-dark font-black text-sm rounded-xl text-center block hover:bg-gray-100 transition shadow-lg">Booking Sekarang</a>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave divider -->
        <div class="wave-bottom">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,121.32,192.73,109.5Z" class="shape-fill"></path>
            </svg>
        </div>
    </div>

    <!-- LAYANAN STUDIO KAMI -->
    <section id="layanan" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-brand-primary font-black tracking-widest text-[10px] uppercase mb-3">LAYANAN KAMI</h5>
                <h2 class="text-3xl md:text-4xl font-black text-brand-dark mb-4">Layanan Studio Kami</h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm">Dari sesi individu hingga keluarga besar, semua tersedia di Imako Studio dengan fotografer profesional dan hasil terbaik.</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                @foreach($categories as $index => $category)
                @php
                    $bgClass = 'bg-white border-gray-100';
                    $textClass = 'text-gray-800';
                    $subClass = 'text-gray-400';
                    
                    $svgs = [
                        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>',
                        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v6m-3-3h6"></path></svg>',
                        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>'
                    ];
                    $svg = $svgs[$index % count($svgs)];
                @endphp
                <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" class="{{ $bgClass }} rounded-3xl p-6 text-center shadow-[0_4px_20px_rgb(0,0,0,0.03)] border relative hover:-translate-y-1 transition duration-300 flex flex-col justify-center min-h-[160px] w-full sm:w-[calc(50%-1rem)] md:w-[calc(20%-1.2rem)] group">
                    <div class="w-12 h-12 mx-auto bg-brand-light text-brand-primary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-brand-primary group-hover:text-white transition duration-300">
                        {!! $svg !!}
                    </div>
                    <h3 class="font-bold {{ $textClass }} text-sm">{{ $category->name }}</h3>
                    <p class="text-[11px] {{ $subClass }} mt-1">{{ $category->packages_count }} Paket Tersedia</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PILIH PAKET -->
    <section id="paket" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-brand-primary font-black tracking-widest text-[10px] uppercase mb-3">HARGA TRANSPARAN</h5>
                <h2 class="text-3xl md:text-4xl font-black text-brand-dark mb-4">Pilih Paket Sesuai Kebutuhanmu</h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm">Semua paket sudah termasuk foto diedit dan dikirim via Google Drive. Booking sekarang!</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @forelse($packages as $index => $package)
                @php
                    $isPopular = $index === 1; // highlight the second package as popular
                    $bgClass = $isPopular ? 'bg-brand-dark' : 'bg-white';
                    $textClass = $isPopular ? 'text-white' : 'text-gray-800';
                    $descClass = $isPopular ? 'text-white/60' : 'text-gray-500';
                    $priceClass = $isPopular ? 'text-white' : 'text-brand-dark';
                    $priceSubClass = $isPopular ? 'text-white/50' : 'text-gray-400';
                    $btnClass = $isPopular ? 'bg-white text-brand-dark font-black hover:bg-gray-100' : 'border-2 border-brand-dark text-brand-dark font-bold hover:bg-brand-dark hover:text-white';
                @endphp
                <div data-aos="fade-up" data-aos-delay="{{ $index * 150 }}" class="{{ $bgClass }} rounded-[32px] p-8 shadow-sm border {{ $isPopular ? 'border-brand-dark' : 'border-gray-100' }} hover:shadow-xl transition duration-300 flex flex-col h-full {{ $isPopular ? 'shadow-2xl relative' : '' }}">
                    @if($isPopular)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-yellow-400 text-brand-dark text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider z-10">#1 TERPOPULER</div>
                    @endif
                    
                    <div class="mb-6 rounded-2xl overflow-hidden aspect-[4/3] bg-black/5 relative group">
                        @if($package->image)
                            <img src="{{ Str::startsWith($package->image, ['http://', 'https://']) ? $package->image : asset('images/paket/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h3 class="font-black text-xl {{ $textClass }}">{{ $package->name }}</h3>
                        <p class="text-xs {{ $descClass }} mt-1">{{ $package->duration_minutes }} menit sesi</p>
                    </div>
                    <div class="mb-8 border-b {{ $isPopular ? 'border-white/10' : 'border-gray-100' }} pb-8">
                        <span class="text-4xl font-black {{ $priceClass }}">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                        <span class="{{ $priceSubClass }} text-sm">/sesi</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        @foreach(explode("\n", $package->description) as $line)
                            @if(trim($line))
                                <li class="flex items-start text-sm {{ $isPopular ? 'text-white/90' : 'text-gray-600' }}"><span class="{{ $isPopular ? 'text-green-400' : 'text-green-500' }} mr-3 font-bold">✓</span> {{ trim($line) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    <a href="/login" class="block w-full py-4 {{ $btnClass }} text-sm rounded-xl text-center transition shadow-lg mt-auto">Pilih Paket</a>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500 py-12">Belum ada paket tersedia saat ini.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- PORTOFOLIO -->
    <section id="portofolio" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h5 class="text-brand-primary font-black tracking-widest text-[10px] uppercase mb-3">GALERI KAMI</h5>
                <h2 class="text-3xl md:text-4xl font-black text-brand-dark mb-4">Portofolio Studio</h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm">Beberapa hasil jepretan terbaik kami dari berbagai momen spesial klien.</p>
            </div>
        </div>

        <style>
            @keyframes scroll-portfolio {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-300px * {{ count($portfolioImages) }} - 1.5rem * {{ count($portfolioImages) }})); }
            }
            .scrolling-portfolio-wrapper {
                display: flex;
                overflow: hidden;
                width: 100%;
                padding: 3rem 0;
            }
            .scrolling-portfolio-content {
                display: flex;
                gap: 1.5rem;
                width: max-content;
                animation: scroll-portfolio 40s linear infinite;
            }
            .scrolling-portfolio-wrapper:hover .scrolling-portfolio-content {
                animation-play-state: paused;
            }
            .portfolio-card {
                width: 300px;
                height: 450px;
                flex-shrink: 0;
                overflow: hidden;
                border-radius: 1rem;
            }
            .portfolio-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }
            .portfolio-card:hover img {
                transform: scale(1.05);
            }
        </style>

        <div class="bg-brand-dark mt-8 shadow-inner">
            <div class="scrolling-portfolio-wrapper">
            <div class="scrolling-portfolio-content">
                @if(count($portfolioImages) > 0)
                    {{-- Loop dua kali untuk membuat efek infinite scroll --}}
                    @for($i = 0; $i < 2; $i++)
                        @foreach($portfolioImages as $image)
                        <div class="portfolio-card shadow-2xl bg-white/5">
                            <img src="{{ asset('images/dokumentasi/' . $image) }}" alt="Portfolio Imako Studio">
                        </div>
                        @endforeach
                    @endfor
                @else
                    <div class="w-full text-center text-white/50 py-12">Belum ada portofolio.</div>
                @endif
            </div>
        </div>
    </section>

    <!-- CARA BOOKING -->
    <section id="cara-booking" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h5 class="text-brand-primary font-black tracking-widest text-[10px] uppercase mb-3">MUDAH & CEPAT</h5>
                <h2 class="text-3xl md:text-4xl font-black text-brand-dark mb-4">Cara Booking di Imako Studio</h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm">Empat langkah mudah untuk booking jadwal fotomu.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex gap-5 items-start">
                    <div class="w-10 h-10 shrink-0 bg-brand-primary text-white font-bold rounded-full flex items-center justify-center">1</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Daftar / Login</h4>
                        <p class="text-sm text-gray-500">Buat akun baru atau login untuk mengakses dashboard pelanggan.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex gap-5 items-start">
                    <div class="w-10 h-10 shrink-0 bg-brand-primary text-white font-bold rounded-full flex items-center justify-center">2</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Pilih Paket & Jadwal</h4>
                        <p class="text-sm text-gray-500">Pilih paket yang kamu mau, lalu tentukan tanggal dan jam di kalender.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex gap-5 items-start">
                    <div class="w-10 h-10 shrink-0 bg-brand-primary text-white font-bold rounded-full flex items-center justify-center">3</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Bayar & Konfirmasi</h4>
                        <p class="text-sm text-gray-500">Transfer pembayaran dan upload bukti bayar. Admin akan segera konfirmasi.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex gap-5 items-start">
                    <div class="w-10 h-10 shrink-0 bg-brand-primary text-white font-bold rounded-full flex items-center justify-center">4</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Sesi Foto & Terima Hasil</h4>
                        <p class="text-sm text-gray-500">Datang ke studio, foto, dan terima link Google Drive hasil editan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONI -->
    <section id="testimoni" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-brand-primary font-black tracking-widest text-[10px] uppercase mb-3">APA KATA MEREKA</h5>
                <h2 class="text-3xl md:text-4xl font-black text-brand-dark mb-4">Testimoni Klien Kami</h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm">Pendapat klien yang sudah menggunakan jasa Imako Studio.</p>
            </div>

            <style>
                @keyframes scroll {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(calc(-350px * 5 - 2rem * 5)); }
                }
                .scrolling-wrapper {
                    display: flex;
                    overflow: hidden;
                    width: 100%;
                    padding: 1rem 0 2rem 0;
                    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
                    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
                }
                .scrolling-content {
                    display: flex;
                    gap: 2rem;
                    width: max-content;
                    animation: scroll 30s linear infinite;
                }
                .scrolling-wrapper:hover .scrolling-content {
                    animation-play-state: paused;
                }
                .review-card {
                    width: 350px;
                    flex-shrink: 0;
                    transition: all 0.3s ease;
                }
                .review-card:hover {
                    background-color: #1A365D !important;
                    transform: translateY(-0.5rem);
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                }
                .review-card:hover * {
                    color: white !important;
                }
            </style>

            <div class="scrolling-wrapper">
                <div class="scrolling-content">
                    @for($i = 0; $i < 2; $i++)
                        @forelse($reviews as $review)
                        <div class="bg-gray-50 border border-gray-100 p-8 rounded-3xl review-card">
                            <div class="text-yellow-400 mb-4 text-sm">{{ str_repeat('⭐', $review->rating) }}</div>
                            <p class="text-gray-600 text-sm mb-6 leading-relaxed line-clamp-4">"{{ $review->comment }}"</p>
                            <div class="flex items-center gap-3">
                                @if($review->booking && $review->booking->user && $review->booking->user->avatar)
                                    <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border-2 border-white/10">
                                        <img src="{{ Str::startsWith($review->booking->user->avatar, ['http']) ? $review->booking->user->avatar : asset('images/profile_akun/user/' . $review->booking->user->avatar) }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 font-bold rounded-full flex items-center justify-center shrink-0 border-2 border-white/10">
                                        {{ collect(explode(' ', $review->booking->user->name ?? 'User'))->map(fn($s) => substr($s, 0, 1))->take(2)->join('') }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="font-bold text-gray-800 text-sm">{{ $review->booking->user->name ?? 'Anonim' }}</h5>
                                    <p class="text-[11px] text-gray-500">{{ $review->booking->package->name ?? 'Paket Studio' }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                            @if($i == 0)
                                <div class="w-full text-center text-gray-500 py-12">Belum ada testimoni. Jadilah yang pertama memberikan review setelah menggunakan layanan kami!</div>
                            @endif
                        @endforelse
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h5 class="text-brand-primary font-black tracking-widest text-[10px] uppercase mb-3">FAQ</h5>
                <h2 class="text-3xl md:text-4xl font-black text-brand-dark mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-gray-500 text-sm">Masih punya pertanyaan? Temukan jawabannya di bawah ini.</p>
            </div>

            <div class="space-y-4">
                <!-- Item 1 -->
                <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-gray-800 select-none">
                        Bagaimana cara booking jadwal foto?
                        <span class="transition duration-300 group-open:-rotate-180 text-brand-primary">
                            <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4 mt-2">
                        Anda dapat melakukan booking melalui website kami dengan mengklik menu "Daftar Gratis", kemudian pilih paket layanan dan jadwal yang masih kosong di kalender. Terakhir, upload bukti pembayaran untuk mengonfirmasi pesanan.
                    </div>
                </details>
                <!-- Item 2 -->
                <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-gray-800 select-none">
                        Apakah bisa ganti jadwal (reschedule)?
                        <span class="transition duration-300 group-open:-rotate-180 text-brand-primary">
                            <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4 mt-2">
                        Bisa. Reschedule maksimal dilakukan H-2 sebelum jadwal foto yang telah dikonfirmasi, dengan syarat jadwal pengganti masih tersedia di kalender booking kami.
                    </div>
                </details>
                <!-- Item 3 -->
                <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-gray-800 select-none">
                        Berapa lama hasil foto selesai diedit?
                        <span class="transition duration-300 group-open:-rotate-180 text-brand-primary">
                            <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4 mt-2">
                        Hasil softfile original akan langsung dikirim maksimal 1x24 jam melalui link Google Drive. Untuk foto yang sudah diedit (retouch), estimasi pengerjaan adalah 3-5 hari kerja tergantung antrean tim editor kami.
                    </div>
                </details>
                <!-- Item 4 -->
                <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-gray-800 select-none">
                        Apakah file asli (raw) juga diberikan?
                        <span class="transition duration-300 group-open:-rotate-180 text-brand-primary">
                            <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4 mt-2">
                        Tentu! Seluruh softfile original beresolusi tinggi akan kami unggah ke Google Drive khusus untuk Anda, bersamaan dengan foto-foto yang sudah diedit sesuai paket yang Anda pilih.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-brand-primary py-24 relative overflow-hidden">
        <div class="hero-glow top-0 right-0 transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-black text-white mb-6">Siap Mengabadikan Momenmu?</h2>
            <p class="text-white/80 mb-10 max-w-xl mx-auto text-sm">Booking jadwal kamu sekarang sebelum penuh. Pilih paket yang sesuai, atur jadwal, dan kami akan siapkan yang terbaik untukmu.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/login" class="px-8 py-4 bg-white text-brand-dark font-black rounded-xl hover:bg-gray-100 transition shadow-lg text-sm">
                    Mulai Booking Sekarang →
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp ?? '6281367004242') }}?text=Hai%20Admin%20Imako,%20mau%20tanya%20sesuatu%20dong" class="px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition text-sm" target="_blank">
                    Hubungi WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-brand-dark text-white pt-20 pb-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('images/(watermark) logo imako white new.png') }}" alt="Logo Imako Studio" class="h-8 w-auto">
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed max-w-sm">{{ $settings->tagline ?? 'Studio foto profesional di Malang. Melayani berbagai jenis dokumentasi dengan kualitas terbaik.' }}</p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-6 text-white tracking-wide">Layanan</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        @foreach($packages->take(5) as $package)
                            <li><a href="#paket" class="hover:text-white transition">{{ $package->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-6 text-white tracking-wide">Kontak</h4>
                    <ul class="space-y-4 text-sm text-white/60">
                        <li>📍 {{ $settings->address ?? 'Jl. Fotografi No. 123, Malang' }}</li>
                        <li>📞 {{ $settings->phone ?? '0812-3456-7890' }}</li>
                        <li>✉️ {{ $settings->email ?? 'hello@imakostudio.com' }}</li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-white/40 text-xs">© {{ date('Y') }} {{ $settings->name ?? 'Imako Studio' }}. All rights reserved.</p>
                <div class="flex gap-4 text-xs text-white/40">
                    <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- AOS & GSAP JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
    <script>
        // Init AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
        });

        // GSAP Hero Animation
        gsap.from(".hero-content > *", {
            y: 50,
            opacity: 0,
            duration: 1,
            stagger: 0.15,
            ease: "power3.out"
        });
        
        gsap.from(".hero-panel", {
            x: 50,
            opacity: 0,
            duration: 1.2,
            delay: 0.3,
            ease: "power3.out"
        });
    </script>
</body>
</html>
