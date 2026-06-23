<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Imako Studio') }} - Pegawai</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    @vite(['resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    
    <!-- Dark Mode OS Check Removed -->
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-[#F4F7FE] dark:bg-slate-900 transition-colors duration-200">
    <audio id="notificationSound" src="/notif.mp3?v={{ time() }}" preload="auto" style="display: none;"></audio>

    <div class="min-h-screen flex">
        
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-white dark:bg-slate-900 shadow-[4px_0_24px_rgba(0,0,0,0.02)] border-r border-gray-100 dark:border-slate-800 flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="h-24 flex items-center px-8 shrink-0 relative border-b border-gray-50 dark:border-slate-800">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center justify-center w-full gap-2.5">
                    <img src="{{ asset('images/(watermark) logo imako grey.png') }}" alt="Logo Imako Studio" class="h-20 w-auto mt-6">
                    <!-- <span class="font-extrabold text-xl tracking-tight text-brand-dark text-center">Imako Studio</span> -->
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden absolute right-8 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <nav class="flex-1 py-8 flex flex-col gap-2">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center gap-3 px-8 py-3 {{ request()->routeIs('employee.dashboard') ? 'text-brand-dark font-bold relative' : 'text-gray-500 font-medium hover:text-brand-primary transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('employee.dashboard') ? 'fill-current' : 'stroke-current' }}" viewBox="0 0 24 24" fill="{{ request()->routeIs('employee.dashboard') ? 'currentColor' : 'none' }}" stroke-width="2"><path d="M12 3L4 9v12h5v-7h6v7h5V9z"/></svg>
                    Dashboard
                    @if(request()->routeIs('employee.dashboard'))
                        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-black rounded-l-md"></div>
                    @endif
                </a>
                
                <a href="{{ route('employee.jadwal') }}" class="flex items-center gap-3 px-8 py-3 {{ request()->routeIs('employee.jadwal') ? 'text-brand-dark font-bold relative' : 'text-gray-500 font-medium hover:text-brand-primary transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('employee.jadwal') ? 'stroke-current' : 'stroke-current' }}" fill="none" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Jadwal Saya
                    @if(request()->routeIs('employee.jadwal'))
                        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-black rounded-l-md"></div>
                    @endif
                </a>
                
                @php
                    $pendingTasksCount = auth()->check() ? auth()->user()->assignments()->whereHas('booking', function ($query) {
                        $query->whereNull('result_link')->where('status', '!=', 'completed');
                    })->count() : 0;
                @endphp
                <a href="{{ route('employee.tugas') }}" class="flex items-center justify-between px-8 py-3 {{ request()->routeIs('employee.tugas') ? 'text-brand-dark font-bold relative' : 'text-gray-500 font-medium hover:text-brand-primary transition-colors' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->routeIs('employee.tugas') ? 'stroke-current' : 'stroke-current' }}" fill="none" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Tugas Saya
                    </div>
                    @if($pendingTasksCount > 0)
                        <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingTasksCount }}</span>
                    @endif
                    @if(request()->routeIs('employee.tugas'))
                        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-black rounded-l-md"></div>
                    @endif
                </a>
                
                <a href="{{ route('employee.kirim_hasil') }}" class="flex items-center gap-3 px-8 py-3 {{ request()->routeIs('employee.kirim_hasil') ? 'text-brand-dark font-bold relative' : 'text-gray-500 font-medium hover:text-brand-primary transition-colors' }}">
                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Hasil
                    @if(request()->routeIs('employee.kirim_hasil'))
                        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-black rounded-l-md"></div>
                    @endif
                </a>
                
                <a href="{{ route('employee.pengajuan_libur') }}" class="flex items-center gap-3 px-8 py-3 {{ request()->routeIs('employee.pengajuan_libur') ? 'text-brand-dark font-bold relative' : 'text-gray-500 font-medium hover:text-brand-primary transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('employee.pengajuan_libur') ? 'stroke-current' : 'stroke-current' }}" fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pengajuan Libur
                    @if(request()->routeIs('employee.pengajuan_libur'))
                        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-black rounded-l-md"></div>
                    @endif
                </a>
                
                <a href="{{ route('employee.profile.edit') }}" class="flex items-center gap-3 px-8 py-3 {{ request()->routeIs('employee.profile.edit') ? 'text-brand-dark font-bold relative' : 'text-gray-500 font-medium hover:text-brand-primary transition-colors' }}">
                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile
                    @if(request()->routeIs('employee.profile.edit'))
                        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-black rounded-l-md"></div>
                    @endif
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-screen">
            <!-- Top Header -->
            <header class="h-24 flex items-center justify-between px-4 sm:px-8 bg-transparent">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-white transition cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>
                    <div>
                        <h1 class="text-2xl sm:text-[32px] font-bold text-brand-dark dark:text-white leading-none transition-colors">{{ $headerTitle ?? 'Dashboard' }}</h1>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">{!! $headerSubtitle ?? \Carbon\Carbon::now()->translatedFormat('l, d F Y') . ' &middot; Selamat pagi, ' . explode(' ', auth()->user()->name)[0] !!}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('employee.profile.edit') }}" class="flex items-center gap-3 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer px-4 py-2 rounded-full shadow-sm border border-gray-100 dark:border-slate-700 group">
                        <div class="w-8 h-8 rounded-full bg-brand-dark overflow-hidden flex items-center justify-center border border-transparent group-hover:border-brand-primary transition-colors">
                            @if(auth()->user()->avatar)
                                <img src="{{ Str::startsWith(auth()->user()->avatar, ['http://', 'https://']) ? auth()->user()->avatar : asset('images/profile_akun/pegawai/' . basename(auth()->user()->avatar)) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-[#3B82F6] text-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ collect(explode(' ', auth()->user()->name))->map(fn($s) => substr($s, 0, 1))->take(2)->join('') }}
                                </div>
                            @endif
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-brand-primary dark:group-hover:text-blue-400 transition-colors hidden sm:block">{{ auth()->user()->name }}</span>
                    </a>
                    
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-800 px-4 py-2 rounded-full shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
                        <div class="relative" x-data="notificationSystem" @click.away="open = false"
                            @user-notification.window="
                                let n = $event.detail;
                                items.unshift({
                                    id: n.id,
                                    type: n.type || 'info',
                                    title: n.title,
                                    desc: n.message,
                                    link: n.link || '#',
                                    time: 'Baru saja'
                                });
                                save();
                                playNotifSound();
                            ">
                            <button @click="toggle" class="text-gray-400 hover:text-brand-primary transition-colors relative flex items-center justify-center p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <!-- Indikator Notifikasi Aktif -->
                                <span x-show="unreadCount > 0" x-cloak class="absolute top-0 right-0.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-slate-800"></span>
                            </button>
                            
                            <!-- Dropdown Notifikasi -->
                            <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden z-50">
                                <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                    <h3 class="text-[13px] font-extrabold text-brand-dark">Notifikasi</h3>
                                    <span x-show="unreadCount > 0" class="text-[10px] font-bold text-brand-primary bg-blue-50 px-2 py-0.5 rounded-full" x-text="unreadCount + ' Baru'"></span>
                                </div>
                                <div class="max-h-[300px] overflow-y-auto">
                                    <template x-for="item in items" :key="item.id">
                                        <a :href="item.link" @click="remove(item.id)" class="block px-5 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors" :class="{ 'bg-blue-50/20': item.type === 'session' }">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5" 
                                                    :class="{
                                                        'bg-blue-50 text-brand-primary': item.type === 'task',
                                                        'bg-green-50 text-green-500': item.type === 'session',
                                                        'bg-red-50 text-red-500': item.type === 'warning'
                                                    }">
                                                    <svg x-show="item.type === 'task'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                    <svg x-show="item.type === 'session'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <svg x-show="item.type === 'warning'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-[11px] font-extrabold text-brand-dark mb-0.5" x-text="item.title"></p>
                                                    <p class="text-[10px] text-gray-500 font-medium leading-relaxed" x-text="item.desc"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold mt-1" x-text="item.time"></p>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                    
                                    <div x-show="items.length === 0" class="px-5 py-8 text-center">
                                        <p class="text-[11px] text-gray-400 font-bold">Tidak ada notifikasi baru.</p>
                                    </div>
                                </div>
                                <div class="px-5 py-2.5 border-t border-gray-50 bg-gray-50/50 text-center" x-show="items.length > 0">
                                    <button @click="clearAll()" class="text-[10px] font-extrabold text-brand-primary hover:text-brand-dark transition-colors">Tandai semua dibaca</button>
                                </div>
                            </div>
                        </div>
                        <div class="h-5 w-px bg-gray-200 mx-2"></div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-700 font-medium text-sm transition-colors" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 sm:p-8 pt-2 overflow-y-auto">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
        
        <!-- Global Toast Notification -->
        <div x-data="{ toasts: [] }" 
             @user-notification.window="
                const notif = $event.detail;
                const id = Date.now();
                toasts.push({ id, title: notif.title || 'Pemberitahuan Baru', message: notif.message || 'Anda memiliki pemberitahuan baru.' });
                setTimeout(() => { toasts = toasts.filter(t => t.id !== id) }, 5000);
             "
             class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none">
            
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-y-10 opacity-0"
                     x-transition:enter-end="translate-y-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-xl p-4 flex gap-3 min-w-[300px] max-w-[350px] pointer-events-auto">
                    <div class="w-8 h-8 rounded-full bg-brand-primary/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-brand-dark dark:text-gray-100 mb-0.5" x-text="toast.title"></h4>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium" x-text="toast.message"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
    @stack('scripts')
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationSystem', () => ({
                open: false,
                lastOpenedAt: localStorage.getItem('employee_notif_opened_at'),
                items: JSON.parse(localStorage.getItem('employee_notif_items')) || [
                    { id: 1, type: 'task', title: 'Tugas Baru dari Admin', desc: 'Anda telah ditugaskan untuk sesi foto "Prewedding Outdoor" besok jam 10:00.', link: '{{ route("employee.tugas") }}', time: 'Baru saja' },
                    { id: 2, type: 'session', title: 'Sesi Foto Berlangsung', desc: 'Sesi foto bersama Bpk. Budi sedang berlangsung. Semangat bekerja!', link: '{{ route("employee.jadwal") }}', time: '10 menit yang lalu' },
                    { id: 3, type: 'warning', title: 'Hasil Foto Belum Diupload', desc: 'Anda belum mengupload link Google Drive untuk klien "Keluarga Cemara".', link: '{{ route("employee.kirim_hasil") }}', time: '2 jam yang lalu' }
                ],
                init() {
                    this.checkExpiration();
                    setInterval(() => this.checkExpiration(), 10000); // Cek tiap 10 detik
                },
                get unreadCount() {
                    return this.items.length;
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open && this.items.length > 0) {
                        if (!this.lastOpenedAt) {
                            this.lastOpenedAt = Date.now().toString();
                            localStorage.setItem('employee_notif_opened_at', this.lastOpenedAt);
                        }
                        playNotifSound();
                    }
                },
                checkExpiration() {
                    if (this.lastOpenedAt) {
                        const elapsed = Date.now() - parseInt(this.lastOpenedAt);
                        if (elapsed > 5 * 60 * 1000) { // 5 menit
                            this.clearAll();
                        }
                    }
                },
                remove(id) {
                    this.items = this.items.filter(i => i.id !== id);
                    this.save();
                },
                clearAll() {
                    this.items = [];
                    this.lastOpenedAt = null;
                    localStorage.removeItem('employee_notif_opened_at');
                    this.save();
                },
                save() {
                    localStorage.setItem('employee_notif_items', JSON.stringify(this.items));
                }
            }));
        });

        // Global sound function
        function playNotifSound() {
            try {
                let audio = document.getElementById('notificationSound');
                if (audio) {
                    audio.currentTime = 0;
                    let playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(e => console.log('Audio Autoplay Blocked:', e));
                    }
                }
            } catch(e) {
                console.error('Audio Error:', e);
            }
        }
    </script>
    <!-- Audio Unlocker for Mobile (Safari Strict) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unlockAudio = () => {
                let audio = document.getElementById('notificationSound');
                if (audio) {
                    let playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            audio.pause();
                            audio.currentTime = 0;
                        }).catch(error => console.log('Unlock pending:', error));
                    }
                    document.removeEventListener('touchstart', unlockAudio);
                    document.removeEventListener('click', unlockAudio);
                }
            };
            document.body.addEventListener('touchstart', unlockAudio, { once: true });
            document.body.addEventListener('click', unlockAudio, { once: true });
        });
    </script>
    <!-- Real-time Notifications Setup -->
    <script>
        const initEmployeeEcho = () => {
            if(window.Echo) {
                window.Echo.private('App.Models.User.{{ auth()->id() }}')
                    .listen('.UserNotificationEvent', (e) => {
                        window.dispatchEvent(new CustomEvent('user-notification', { detail: e }));
                    });
            } else {
                setTimeout(initEmployeeEcho, 200);
            }
        };
        initEmployeeEcho();
    </script>
</body>
</html>
