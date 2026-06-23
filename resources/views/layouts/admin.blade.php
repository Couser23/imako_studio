<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Imako Studio</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            dark: '#1e1b4b',
                            primary: '#2B5488',
                            blue: '#4338ca',
                            bg: '#f8fafc',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Sidebar */
        .sidebar-item:hover, .sidebar-item.active { color: #1e1b4b; font-weight: 700; }
        .sidebar-item.active { border-right: 4px solid #1e1b4b; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body class="text-gray-800 bg-slate-50 antialiased overflow-hidden transition-colors duration-200">

    <div class="flex h-screen w-full">
        
        <!-- Sidebar Backdrop (Mobile) -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="bg-white w-64 h-full flex flex-col transition-all duration-300 ease-in-out fixed lg:relative z-50 -translate-x-full lg:translate-x-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)] border-r border-transparent">
            <!-- Sidebar Header / Logo -->
            <div class="h-24 flex items-center px-8 shrink-0 relative">
                <div class="flex items-center justify-center w-full gap-2.5">
                    <!-- Logo Icon (Bisa diganti dengan tag <img> nantinya) -->
                    <!-- <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-dark to-brand-primary flex items-center justify-center text-white shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><circle cx="12" cy="13" r="3" stroke-width="2"></circle></svg>
                    </div> -->
                    <img src="{{ asset('images/(watermark) logo imako grey.png') }}" alt="Logo Imako Studio" class="h-20 w-auto mt-6">
                    <!-- <span class="font-extrabold text-xl tracking-tight text-brand-dark text-center">Imako Studio</span> -->
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden absolute right-8 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <div class="flex-1 overflow-y-auto py-2 space-y-2">
                <a href="/admin/dashboard" class="sidebar-item flex items-center px-8 py-3 text-sm text-gray-400 font-medium {{ request()->is('admin/dashboard') ? 'active text-brand-dark' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.packages.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.packages.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Kelola Paket
                </a>

                <a href="{{ route('admin.categories.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Kategori & Tambahan
                </a>

                <a href="{{ route('admin.finance.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.finance.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Pembukuan
                </a>

                <a href="{{ route('admin.payments.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.payments.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Validasi & Metode
                </a>

                <a href="{{ route('admin.schedules.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.schedules.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Semua Jadwal
                </a>

                <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Semua pengguna
                </a>

                <a href="{{ route('admin.employee-schedules.index') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.employee-schedules.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Jadwal Pegawai
                </a>

                <a href="{{ route('admin.leave-requests.index') }}" class="sidebar-item flex items-center justify-between px-8 py-3 text-sm font-medium {{ request()->routeIs('admin.leave-requests.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l3-3 5.5 1 4.5-4h2l-2.5 5 1.5 1.5M3 10v4l3 3h4l5.5-2.5L21 16v-2l-3.5-3M3 10L12 19"></path></svg>
                        Pengajuan Libur
                    </div>
                    @php
                        $pendingLeavesCount = \App\Models\LeaveRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingLeavesCount > 0)
                        <span class="bg-red-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $pendingLeavesCount }}</span>
                    @endif
                </a>

                <a href="{{ route('profile.edit') }}" class="sidebar-item flex items-center px-8 py-3 text-sm font-medium mt-8 {{ request()->routeIs('profile.*') ? 'active text-brand-dark' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile
                </a>

                <!-- Mobile Accessible Logout -->
                <!-- <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
                    @csrf
                    <button type="submit" class="sidebar-item w-full flex items-center px-8 py-3 text-sm font-medium text-red-500 hover:text-red-700 hover:bg-red-50 transition cursor-pointer">
                        <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form> -->
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-brand-bg relative">
            
            <!-- Topbar Area (Title and Controls) -->
            @if (!View::hasSection('hide_topbar'))
            <header class="px-8 pt-8 pb-4 flex items-start justify-between shrink-0 z-30">
                <div class="flex gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>
                    <div>
                        <p class="text-sm font-semibold text-brand-primary/60 mb-0.5">@yield('pre-title', 'Dashboard')</p>
                        <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight">@yield('title', 'Dashboard')</h1>
                        <p class="text-sm font-medium text-brand-primary/60 mt-1">@yield('subtitle', 'Ringkasan hari ini')</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Profile Chip Dropdown -->
                    <div class="relative" x-data="{ 
                            open: false, 
                            originalName: '{{ auth()->user() ? addslashes(auth()->user()->name) : '' }}',
                            originalEmail: '{{ auth()->user() ? addslashes(auth()->user()->email) : '' }}',
                            originalPhone: '{{ auth()->user() ? addslashes(auth()->user()->phone_number) : '' }}',
                            name: '{{ auth()->user() ? addslashes(auth()->user()->name) : '' }}',
                            email: '{{ auth()->user() ? addslashes(auth()->user()->email) : '' }}',
                            phone: '{{ auth()->user() ? addslashes(auth()->user()->phone_number) : '' }}',
                            get isDirty() {
                                return this.name !== this.originalName || this.email !== this.originalEmail || this.phone !== this.originalPhone;
                            }
                        }" @click.outside="open = false">
                        
                        <div @click="open = !open" class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-white border border-gray-200 flex items-center justify-center shrink-0">
                                @if(auth()->user() && auth()->user()->avatar)
                                    <img src="{{ asset('images/profile_akun/' . strtolower(auth()->user()->role ?? 'user') . '/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-contain p-0.5">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user() ? auth()->user()->name : 'User') }}&background=1e1b4b&color=fff" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="hidden sm:block text-sm font-bold text-brand-dark pr-2">{{ auth()->user() ? auth()->user()->name : 'User' }}</span>
                        </div>

                        <!-- Dropdown Panel -->
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden z-50" style="display: none;">
                            <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="text-sm font-extrabold text-brand-dark">Update Profil Cepat</h3>
                            </div>
                            
                            <form action="{{ route('profile.update') }}" method="POST" class="p-5 flex flex-col gap-3">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" x-model="name" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-3 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition" required>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 mb-1">Email</label>
                                    <input type="email" name="email" x-model="email" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-3 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition" required>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 mb-1">Nomor HP</label>
                                    <input type="text" name="phone_number" x-model="phone" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-3 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition">
                                </div>

                                <!-- Simpan Button (only shows when dirty) -->
                                <button type="submit" x-show="isDirty" x-transition class="mt-2 w-full px-4 py-2 bg-brand-dark text-white text-[11px] font-extrabold rounded-lg hover:bg-brand-primary transition shadow-sm" style="display: none;">
                                    Simpan Perubahan
                                </button>
                            </form>
                            
                            <div class="px-5 py-3 border-t border-gray-50 text-center bg-gray-50/50 flex justify-center">
                                <a href="{{ route('profile.edit') }}" class="text-[10px] font-extrabold text-brand-primary hover:text-brand-blue transition">Lihat Profil Lengkap</a>
                            </div>
                        </div>
                    </div>

                    <!-- Audio Notifikasi -->
                    @php
                        $soundPref = auth()->user()->notification_preferences['notification_sound'] ?? 'Ting (Default)';
                        $soundFile = 'notif.mp3';
                        if ($soundPref == 'Bell Ring') $soundFile = 'bell.mp3';
                        elseif ($soundPref == 'Pop Mellow') $soundFile = 'pop.mp3';
                        elseif ($soundPref == 'Circles') $soundFile = 'chime.mp3';
                    @endphp
                    <audio id="notificationSound" src="/{{ $soundFile }}?v={{ time() }}" preload="auto" style="display: none;"></audio>

                    <!-- Action Buttons Pill -->
                    <div class="flex items-center bg-white rounded-full shadow-sm border border-gray-100 p-1.5">
                        <!-- Notification Dropdown -->
                        <div class="relative" x-data="{ open: false, hasUnread: false, notifications: [] }"
                             @new-notification.window="hasUnread = true; notifications.unshift($event.detail)"
                             >
                            <button @click="open = !open; hasUnread = false" @click.outside="open = false" class="relative p-2 text-brand-dark/70 hover:text-brand-dark hover:bg-gray-50 rounded-full transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <span x-show="hasUnread" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white animate-pulse" style="display: none;"></span>
                            </button>

                            <!-- Dropdown Panel -->
                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden z-50" style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                                    <h3 class="text-sm font-extrabold text-brand-dark">Notifikasi</h3>
                                    <span x-show="notifications.length > 0" x-text="notifications.length + ' Baru'" class="text-[10px] font-bold text-brand-primary bg-brand-primary/10 px-2 py-0.5 rounded-full" style="display: none;"></span>
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    <template x-for="notif in notifications" :key="notif.id || Date.now()">
                                        <a href="/admin/payments" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 relative">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-brand-dark mb-0.5" x-text="notif.title || notif.user_name"></p>
                                                    <p class="text-[10px] text-gray-500 mb-1 leading-snug" x-text="notif.message || ('Telah mengunggah bukti pembayaran sebesar Rp ' + notif.amount)"></p>
                                                    <p class="text-[9px] font-bold text-gray-400">Baru saja</p>
                                                </div>
                                            </div>
                                            <div class="absolute w-2 h-2 rounded-full bg-brand-primary top-1/2 -translate-y-1/2 right-4"></div>
                                        </a>
                                    </template>
                                    
                                    <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-400 text-xs font-medium">
                                        Belum ada notifikasi baru.
                                    </div>
                                </div>
                                <div class="px-4 py-3 border-t border-gray-50 text-center bg-gray-50/50">
                                    <a href="{{ route('admin.payments.index') }}" class="text-[10px] font-extrabold text-brand-primary hover:text-brand-blue transition">Lihat semua pesanan</a>
                                </div>
                            </div>
                        </div>


                        
                        <div class="w-px h-5 bg-gray-200 mx-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold text-red-500 hover:bg-red-50 rounded-full transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <div class="page-content flex-1 overflow-y-auto px-8 pb-8 opacity-0 translate-y-4">
                @yield('content')
            </div>

        </main>

        <!-- Global Toast Notification -->
        <div x-data="{ toasts: [] }" 
             @new-notification.window="
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
                     class="bg-white border border-gray-200 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-xl p-4 flex gap-3 min-w-[300px] max-w-[350px] pointer-events-auto">
                    <div class="w-8 h-8 rounded-full bg-brand-primary/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-brand-dark mb-0.5" x-text="toast.title"></h4>
                        <p class="text-[10px] text-gray-500 font-medium" x-text="toast.message"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Scripts -->
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
    </script>
    @stack('scripts')
    
    <!-- Audio Unlocker for Mobile (Safari Strict) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unlockAudio = () => {
                let audio = document.getElementById('notificationSound');
                if (audio) {
                    // Safari butuh play() langsung tanpa di-mute terlebih dahulu
                    let playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            audio.pause();
                            audio.currentTime = 0;
                        }).catch(error => {
                            console.log('Unlock pending:', error);
                        });
                    }
                    document.removeEventListener('touchstart', unlockAudio);
                    document.removeEventListener('click', unlockAudio);
                }
            };
            document.body.addEventListener('touchstart', unlockAudio, { once: true });
            document.body.addEventListener('click', unlockAudio, { once: true });
        });
    </script>

    <!-- Script WebSockets Vanilla JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @php
                $isRealtimeEnabled = auth()->user()->notification_preferences['notifikasi_realtime'] ?? true;
            @endphp

            @if($isRealtimeEnabled)
            const initEcho = () => {
                if(window.Echo) {
                    window.Echo.channel('admin.notifications')
                        .listen('.NewPaymentUploaded', (e) => {
                            console.log("EVENT DITERIMA:", e);
                            window.dispatchEvent(new CustomEvent('new-notification', { detail: {
                                type: 'payment',
                                title: 'Pesanan Masuk',
                                message: `Rp ${e.amount || 0} dari ${e.user_name || 'User'} untuk ${e.package_name || 'Paket'}`
                            } }));
                            playNotifSound();
                        })
                        .listen('.AdminNotificationEvent', (e) => {
                            window.dispatchEvent(new CustomEvent('new-notification', { detail: {
                                type: e.type,
                                title: e.title,
                                message: e.message
                            } }));
                            playNotifSound();
                        });

                    function playNotifSound() {
                            try {
                                let audio = document.getElementById('notificationSound');
                                if (audio) {
                                    audio.currentTime = 0;
                                    let promise = audio.play();
                                    if (promise !== undefined) {
                                        promise.catch(err => {
                                            console.log('Autoplay blocked by browser policy:', err);
                                        });
                                    }
                                }
                            } catch(err) {
                                console.error('Audio error', err);
                            }
                        }
                } else {
                    setTimeout(initEcho, 200);
                }
            };
            initEcho();
            @endif
            
            // Session termination listener (always active regardless of notification settings)
            const initSessionEcho = () => {
                if(window.Echo) {
                    window.Echo.private(`session.{{ session()->getId() }}`)
                        .listen('SessionTerminated', (e) => {
                            // If this device's session was terminated, redirect immediately to login
                            window.location.href = '/login';
                        });
                } else {
                    setTimeout(initSessionEcho, 200);
                }
            };
            initSessionEcho();
        });
    </script>

    <!-- GSAP Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.page-content', {
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: "power3.out"
            });
        });
    </script>
</body>
</html>
