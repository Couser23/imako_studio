<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Imako Studio') }} - User Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#2B5488',
                            light: '#4278BA',
                            dark: '#1A365D',
                            bg: '#F8FAFC'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .sidebar-link { transition: all 0.2s ease-in-out; }
        .sidebar-link.active { color: #2B5488; font-weight: 700; border-right: 3px solid #2B5488; background-color: #F1F5F9; }
        .sidebar-link:hover:not(.active) { background-color: #F8FAFC; color: #475569; }
        
        /* Dark Mode Global Overrides */
        html.dark body { background-color: #0F172A; color: #F8FAFC; }
        html.dark .bg-[#F8FAFC] { background-color: #0F172A !important; }
        html.dark .bg-white { background-color: #1E293B !important; border-color: #334155 !important; }
        html.dark .text-brand-dark { color: #F8FAFC !important; }
        html.dark .text-gray-800, html.dark .text-gray-900 { color: #F1F5F9 !important; }
        html.dark .text-gray-700 { color: #E2E8F0 !important; }
        html.dark .text-gray-600 { color: #CBD5E1 !important; }
        html.dark .text-gray-500 { color: #94A3B8 !important; }
        html.dark .bg-gray-50, html.dark .bg-gray-100, html.dark .bg-gray-200 { background-color: #334155 !important; }
        html.dark .border-gray-50, html.dark .border-gray-100, html.dark .border-gray-200, html.dark .border-gray-300 { border-color: #475569 !important; }
        html.dark aside, html.dark .sidebar-link:hover:not(.active) { background-color: #1E293B !important; }
        html.dark .sidebar-link.active { background-color: #334155 !important; color: #60A5FA !important; border-color: #60A5FA !important; }
        html.dark .text-brand-blue { color: #60A5FA !important; }
        html.dark input, html.dark select, html.dark textarea { background-color: #0F172A !important; color: #F8FAFC !important; border-color: #475569 !important; }
        html.dark .bg-\[\#E8F0FE\] { background-color: #1E3A8A !important; }
        html.dark .bg-\[\#FFF8ED\] { background-color: #451A03 !important; border-color: #78350F !important; }
        html.dark .text-\[\#D97706\] { color: #FBBF24 !important; }
        html.dark .bg-\[\#FFE5B2\] { background-color: #78350F !important; }
        html.dark .bg-\[\#ECFDF5\] { background-color: #064E3B !important; border-color: #065F46 !important; }
        html.dark .text-\[\#10B981\] { color: #34D399 !important; }
        html.dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5) !important; }
        html.dark [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-gray-800 flex h-screen overflow-hidden">
    <audio id="notificationSound" src="/notif.mp3?v={{ time() }}" preload="auto" style="display: none;"></audio>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-white shadow-[4px_0_24px_rgba(0,0,0,0.02)] flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out">
        <div>
            <!-- Logo -->
            <div class="h-24 flex items-center px-8 shrink-0 relative">
                <div class="flex items-center justify-center w-full gap-2.5">
                    <img src="{{ asset('images/(watermark) logo imako grey.png') }}" alt="Logo Imako Studio" class="h-20 w-auto mt-8">
                    <!-- <span class="font-extrabold text-xl tracking-tight text-brand-dark text-center">Imako Studio</span> -->
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden absolute right-8 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 flex flex-col gap-2">
                <a href="{{ route('user.dashboard') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                
                <a href="{{ route('user.catalog.index') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('user.catalog.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Katalog Paket
                </a>

                <a href="{{ route('user.schedules.index') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('user.schedules.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Cek Jadwal
                </a>

                <a href="{{ route('user.bookings.create') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('user.bookings.create') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Booking Baru
                </a>

                <a href="{{ route('user.bookings.index') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('user.bookings.index') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Status Pesanan
                </a>

                <a href="{{ route('user.results.index') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('user.results.index') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Hasil Foto
                </a>

                <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center gap-3 px-8 py-3 text-sm text-gray-500 font-medium {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile
                </a>
            </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F8FAFC] dark:bg-[#0F172A] transition-colors duration-300">
        
        <!-- Topbar -->
        <header class="h-24 bg-transparent flex items-center justify-between px-4 sm:px-10 shrink-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-white transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                <div>
                    <p class="text-[10px] sm:text-xs text-gray-500 mb-1">Pages / {{ $title ?? 'Dashboard' }}</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-brand-dark">{{ $title ?? 'Dashboard' }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- User Badge -->
                <a href="{{ route('profile.edit') }}" class="bg-white px-4 py-2 rounded-full shadow-sm flex items-center gap-3 hover:bg-gray-50 transition cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden shrink-0">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('images/profile_akun/' . strtolower(Auth::user()->role ?? 'user') . '/' . Auth::user()->avatar) }}" alt="User avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2B5488&color=fff" alt="User avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="text-sm font-semibold text-brand-dark hidden sm:block">{{ Auth::user()->name }}</div>
                </a>

                @php
                    $userNotifications = [];
                    $recentBookings = Auth::user()->bookings()->with(['payments'])->latest()->take(20)->get();

                    foreach ($recentBookings as $booking) {
                        foreach ($booking->payments as $payment) {
                            if ($payment->status === 'verified') {
                                $userNotifications[] = [
                                    'id' => 'pay_' . $payment->id,
                                    'text' => "Pembayaran #IMK-{$booking->booking_code} diverifikasi.",
                                    'time' => $payment->updated_at,
                                    'link' => route('user.bookings.index')
                                ];
                            }
                        }

                        if ($booking->status === 'confirmed') {
                            $bookingDate = \Carbon\Carbon::parse($booking->booking_date);
                            if ($bookingDate->isToday() || $bookingDate->isTomorrow()) {
                                $userNotifications[] = [
                                    'id' => 'sess_' . $booking->id,
                                    'text' => "Sesi #IMK-{$booking->booking_code} mulai {$bookingDate->translatedFormat('d M')}.",
                                    'time' => $booking->updated_at,
                                    'link' => route('user.schedules.index')
                                ];
                            }
                        }

                        if ($booking->status === 'completed' && $booking->result_link) {
                            $userNotifications[] = [
                                'id' => 'res_' . $booking->id,
                                'text' => "Hasil #IMK-{$booking->booking_code} siap diunduh!",
                                'time' => $booking->updated_at,
                                'link' => route('user.results.index')
                            ];
                        }
                    }

                    usort($userNotifications, function($a, $b) {
                        return $b['time'] <=> $a['time'];
                    });

                    $userNotifications = array_slice($userNotifications, 0, 5);
                    
                    $formattedNotifs = array_map(function($n) {
                        return [
                            'id' => $n['id'],
                            'text' => $n['text'],
                            'time' => $n['time']->diffForHumans(),
                            'link' => $n['link']
                        ];
                    }, $userNotifications);
                @endphp
                <script>
                    window.userNotificationData = @json($formattedNotifs);
                </script>

                <!-- Tools -->
                <div class="bg-white px-4 py-2.5 rounded-full shadow-sm flex items-center gap-4 text-gray-400">
                    <div x-data="{ 
                            open: false,
                            storageData: JSON.parse(localStorage.getItem('userNotifsMeta') || '{}'),
                            notifs: window.userNotificationData,
                            displayNotifs: [],
                            unreadCount: 0,
                            
                            init() {
                                this.filterNotifs();
                                setInterval(() => this.filterNotifs(), 60000); // Check expiry every minute
                            },
                            
                            filterNotifs() {
                                const now = Date.now();
                                this.displayNotifs = this.notifs.filter(n => {
                                    const meta = this.storageData[n.id];
                                    if (!meta) return true; // never seen
                                    if (meta.clicked) return false; // clicked -> hide forever
                                    // if seen, check if 5 mins passed
                                    if (meta.seenAt && (now - meta.seenAt) > 5 * 60 * 1000) return false;
                                    return true;
                                });
                                
                                this.unreadCount = this.displayNotifs.filter(n => !this.storageData[n.id]).length;
                            },

                            toggle() {
                                this.open = !this.open;
                                if (this.open) {
                                    let updated = false;
                                    const now = Date.now();
                                    if (this.unreadCount > 0) {
                                        this.playSound();
                                    }
                                    this.displayNotifs.forEach(n => {
                                        if (!this.storageData[n.id]) {
                                            this.storageData[n.id] = { seenAt: now };
                                            updated = true;
                                        }
                                    });
                                    if (updated) {
                                        localStorage.setItem('userNotifsMeta', JSON.stringify(this.storageData));
                                        this.unreadCount = 0;
                                    }
                                }
                            },

                            clickNotif(id) {
                                this.storageData[id] = { clicked: true };
                                localStorage.setItem('userNotifsMeta', JSON.stringify(this.storageData));
                            },
                            
                            markAllAsRead() {
                                this.displayNotifs.forEach(n => {
                                    this.storageData[n.id] = { clicked: true };
                                });
                                localStorage.setItem('userNotifsMeta', JSON.stringify(this.storageData));
                                this.filterNotifs();
                            },
                            
                            playSound() {
                                try {
                                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                                    const oscillator = audioCtx.createOscillator();
                                    const gainNode = audioCtx.createGain();
                                    
                                    oscillator.connect(gainNode);
                                    gainNode.connect(audioCtx.destination);
                                    
                                    oscillator.type = 'sine';
                                    oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // Note A5
                                    oscillator.frequency.exponentialRampToValueAtTime(1760, audioCtx.currentTime + 0.1); // Slide ke A6
                                    
                                    gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                                    gainNode.gain.linearRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
                                    gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                                    
                                    oscillator.start(audioCtx.currentTime);
                                    oscillator.stop(audioCtx.currentTime + 0.3);
                                } catch(e) {}
                            }
                        }" class="relative flex items-center"
                        @user-notification.window="
                            let n = $event.detail;
                            notifs.unshift({
                                id: n.id,
                                text: n.message,
                                time: 'Baru saja',
                                link: n.link || '#'
                            });
                            filterNotifs();
                            playSound();
                        ">
                        <button @click="toggle()" @click.away="open = false" class="relative hover:text-brand-blue transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <template x-if="unreadCount > 0">
                                <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full border-2 border-white" x-text="unreadCount"></span>
                            </template>
                        </button>
                        <div x-show="open" x-cloak class="absolute right-0 top-full mt-4 w-72 bg-white rounded-xl shadow-lg border border-gray-100 p-4 z-50">
                            <h4 class="text-xs font-bold text-gray-800 mb-2 border-b border-gray-50 pb-2">Notifikasi (<span x-text="displayNotifs.length"></span>)</h4>
                            
                            <template x-for="notif in displayNotifs" :key="notif.id">
                                <a :href="notif.link" @click="clickNotif(notif.id)" class="block text-xs text-gray-600 py-2 border-b border-gray-50 hover:bg-gray-50 hover:text-brand-blue transition rounded px-2">
                                    <span x-text="notif.text"></span>
                                    <span class="block text-[9px] text-gray-400 mt-0.5" x-text="notif.time"></span>
                                </a>
                            </template>
                            
                            <template x-if="displayNotifs.length > 0">
                                <div class="mt-2 text-center border-t border-gray-50 pt-2">
                                    <button @click="markAllAsRead()" class="text-[10px] font-bold text-brand-blue hover:text-blue-800 transition">Tandai semua dibaca</button>
                                </div>
                            </template>
                            
                            <template x-if="displayNotifs.length === 0">
                                <div class="text-xs text-gray-500 py-4 text-center">Belum ada notifikasi</div>
                            </template>
                        </div>
                    </div>
                    
                    <div class="w-px h-5 bg-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 transition flex items-center gap-1 text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 overflow-y-auto px-10 pb-10 flex flex-col">
            <div class="page-content flex-1 opacity-0 translate-y-4">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-[#E8F8EE] border border-[#A7E6C0] rounded-xl p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-[#22C55E] text-white rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-sm font-extrabold text-gray-800">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <p class="text-sm font-extrabold text-gray-800">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="text-sm font-extrabold text-gray-800">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif
                
                @yield('content')
            </div>
            
            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400 font-medium">&copy; {{ date('Y') }} Imako Studio, Semua hak dilindungi.</p>
                <a href="#" class="text-xs text-gray-400 font-medium hover:text-brand-blue transition">Terms of Use</a>
        </main>
        
    </div>

    <!-- Global Toast Notification -->
    <div x-data="{ toasts: [] }" 
         @user-notification.window="
            console.log('Alpine caught user-notification event:', $event.detail);
            const notif = $event.detail;
            const id = Date.now();
            toasts.push({ id, title: notif.title || 'Pemberitahuan Baru', message: notif.message || 'Anda memiliki pemberitahuan baru.' });
            setTimeout(() => { toasts = toasts.filter(t => t.id !== id) }, 5000);
         "
         class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none">
        
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

    @stack('scripts')
    <script>
        function themeConfig() {
            return {
                darkMode: localStorage.getItem('theme') === 'dark',
                toggle() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                }
            }
        }
    </script>
    <!-- Real-time Notifications Setup -->
    <script>
        const initUserEcho = () => {
            if(window.Echo) {
                console.log('Echo initialized for User:', '{{ auth()->id() }}');
                window.Echo.private('App.Models.User.{{ auth()->id() }}')
                    .listen('.UserNotificationEvent', (e) => {
                        console.log('User Notification Received via Echo:', e);
                        window.dispatchEvent(new CustomEvent('user-notification', { detail: e }));
                        playNotifSound();
                    })
                    .error((e) => {
                        console.error('Echo User Channel Error:', e);
                    });
            } else {
                setTimeout(initUserEcho, 200);
            }
        };
        initUserEcho();

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

        // Audio Unlocker for Mobile
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

    <!-- GSAP Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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
