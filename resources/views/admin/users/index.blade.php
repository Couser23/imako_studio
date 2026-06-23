@extends('layouts.admin')

@section('title', 'Semua pengguna')
@section('pre-title', 'Manajemen Akun')
@section('subtitle', 'Kelola data pelanggan dan pegawai')

@section('content')

<!-- Header & Subtitle -->
<!-- <div class="mb-6">
    <h2 class="text-sm font-bold text-gray-400">Kelola akun pelanggan dan pegawai studio</h2>
</div> -->

<!-- 4 Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 relative overflow-hidden">
        <div class="absolute right-4 top-4 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <p class="text-[11px] font-bold text-gray-400 mb-1">Total Pengguna</p>
        <h3 class="text-2xl font-black text-brand-dark mb-1">{{ $stats['total'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Semua akun terdaftar</p>
    </div>
    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 relative overflow-hidden">
        <div class="absolute right-4 top-4 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <p class="text-[11px] font-bold text-gray-400 mb-1">Pelanggan</p>
        <h3 class="text-2xl font-black text-blue-500 mb-1">{{ $stats['pelanggan'] }}</h3>
        <p class="text-[10px] font-bold text-blue-500">Akun terdaftar</p>
    </div>
    <!-- Card 3 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 relative overflow-hidden">
        <div class="absolute right-4 top-4 w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
        </div>
        <p class="text-[11px] font-bold text-gray-400 mb-1">Pegawai Aktif</p>
        <h3 class="text-2xl font-black text-purple-600 mb-1">{{ $stats['pegawai'] }}</h3>
        <p class="text-[10px] font-bold text-purple-400">Tim Studio</p>
    </div>
    <!-- Card 4 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 relative overflow-hidden">
        <div class="absolute right-4 top-4 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
        </div>
        <p class="text-[11px] font-bold text-gray-400 mb-1">Akun Admin</p>
        <h3 class="text-2xl font-black text-gray-500 mb-1">{{ $stats['admin'] }}</h3>
        <p class="text-[10px] font-bold text-gray-400">Hak Akses Penuh</p>
    </div>
</div>

<!-- ================= ADMIN SECTION ================= -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-b border-gray-200 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-brand-dark">Administrator</h3>
                <p class="text-[10px] font-bold text-gray-400">{{ $admins->count() }} akun terdaftar</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button onclick="openAddEmployeeModal('admin')" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold rounded-lg shadow-sm flex items-center gap-2 transition whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Admin
            </button>
        </div>
    </div>

    <!-- Grid Body -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            @forelse($admins as $admin)
            <!-- Admin Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md hover:border-brand-primary/30 transition cursor-pointer relative group" onclick="openEmployeeDetailModal({{ $admin->id }})">
                <div class="absolute right-4 top-4 flex gap-1.5">
                    @php
                        $isOnLeave = false;
                        $today = \Carbon\Carbon::today()->format('Y-m-d');
                        foreach($admin->leaveRequests ?? [] as $leave) {
                            if ($leave->status === 'approved' && $today >= $leave->start_date->format('Y-m-d') && $today <= $leave->end_date->format('Y-m-d')) {
                                $isOnLeave = true;
                                break;
                            }
                        }
                    @endphp
                    @if($isOnLeave)
                        <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[9px] font-extrabold rounded-full shadow-sm border border-red-200">Sedang Libur</span>
                    @elseif($admin->email_verified_at)
                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-[9px] font-extrabold rounded-full">Belum Verifikasi</span>
                    @endif
                </div>
                @php
                    $initials = collect(explode(' ', $admin->name))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                @endphp
                @if($admin->avatar)
                <div class="w-12 h-12 rounded-xl border border-gray-100 flex items-center justify-center mb-4 shadow-sm group-hover:scale-105 transition-transform overflow-hidden bg-white">
                    <img src="{{ asset('images/profile_akun/' . strtolower($admin->role ?? 'admin') . '/' . $admin->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                @else
                <div class="w-12 h-12 rounded-xl bg-gray-800 text-white flex items-center justify-center text-lg font-black mb-4 shadow-sm group-hover:scale-105 transition-transform">{{ strtoupper($initials) }}</div>
                @endif
                <h4 class="text-sm font-extrabold text-brand-dark mb-0.5">{{ $admin->name }}</h4>
                <p class="text-[11px] font-bold text-gray-400 mb-4 flex items-center gap-1.5">
                    {{ ['pegawai' => 'Pegawai', 'admin' => 'Admin', 'user' => 'Pelanggan'][$admin->role] ?? ucfirst($admin->role) }}
                </p>
                
                <div class="grid grid-cols-2 gap-2 pt-4 border-t border-gray-100 text-center">
                    <div class="border-r border-gray-100">
                        <h5 class="text-[11px] font-bold text-gray-600 mb-0.5 mt-0.5">{{ $admin->created_at->format('M Y') }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Bergabung</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-black text-brand-dark mb-0.5 truncate px-1" title="{{ $admin->email }}">{{ explode('@', $admin->email)[0] }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Email</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-400 text-sm">Belum ada administrator.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- ================= PEGAWAI SECTION ================= -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-b border-gray-200 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-brand-dark">Pegawai / Fotografer</h3>
                <p class="text-[10px] font-bold text-gray-400">{{ $employees->count() }} akun terdaftar</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-56">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Cari pegawai..." class="bg-gray-50 border border-gray-200 text-gray-600 text-xs font-bold rounded-lg focus:border-brand-primary focus:ring-1 focus:ring-brand-primary block w-full pl-9 py-2 transition-all outline-none placeholder-gray-400 shadow-sm">
            </div>
            <button onclick="openAddEmployeeModal()" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold rounded-lg shadow-sm flex items-center gap-2 transition whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah
            </button>
        </div>
    </div>

    <!-- Grid Body -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            @forelse($employees as $employee)
            <!-- Employee Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md hover:border-brand-primary/30 transition cursor-pointer relative group" onclick="openEmployeeDetailModal({{ $employee->id }})">
                <div class="absolute right-4 top-4 flex gap-1.5">
                    @php
                        $isOnLeave = false;
                        $today = \Carbon\Carbon::today()->format('Y-m-d');
                        foreach($employee->leaveRequests ?? [] as $leave) {
                            if ($leave->status === 'approved' && $today >= $leave->start_date->format('Y-m-d') && $today <= $leave->end_date->format('Y-m-d')) {
                                $isOnLeave = true;
                                break;
                            }
                        }
                    @endphp
                    @if($isOnLeave)
                        <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[9px] font-extrabold rounded-full shadow-sm border border-red-200">Sedang Libur</span>
                    @elseif($employee->email_verified_at)
                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-[9px] font-extrabold rounded-full">Belum Verifikasi</span>
                    @endif
                </div>
                @php
                    $bgColors = ['bg-purple-500', 'bg-pink-500', 'bg-blue-500', 'bg-orange-500', 'bg-teal-500'];
                    $bgColor = $bgColors[array_rand($bgColors)];
                    $initials = collect(explode(' ', $employee->name))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                @endphp
                @if($employee->avatar)
                <div class="w-12 h-12 rounded-xl border border-gray-100 flex items-center justify-center mb-4 shadow-sm group-hover:scale-105 transition-transform overflow-hidden bg-white">
                    <img src="{{ asset('images/profile_akun/' . strtolower($employee->role ?? 'user') . '/' . $employee->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                @else
                <div class="w-12 h-12 rounded-xl {{ $bgColor }} text-white flex items-center justify-center text-lg font-black mb-4 shadow-sm group-hover:scale-105 transition-transform">{{ strtoupper($initials) }}</div>
                @endif
                <h4 class="text-sm font-extrabold text-brand-dark mb-0.5">{{ $employee->name }}</h4>
                <p class="text-[11px] font-bold text-gray-400 mb-4 flex items-center gap-1.5">
                    {{ ['pegawai' => 'Pegawai', 'admin' => 'Admin', 'user' => 'Pelanggan'][$employee->role] ?? ucfirst($employee->role) }}
                </p>
                
                <div class="grid grid-cols-3 gap-2 pt-4 border-t border-gray-100 text-center">
                    <div>
                        <h5 class="text-sm font-black text-brand-dark mb-0.5">{{ $employee->assignments()->count() ?? 0 }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Total sesi</p>
                    </div>
                    <div class="border-l border-r border-gray-100">
                        <h5 class="text-sm font-black text-brand-dark mb-0.5">{{ $employee->assignments()->whereMonth('created_at', \Carbon\Carbon::now()->month)->count() ?? 0 }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Bulan ini</p>
                    </div>
                    <div>
                        <h5 class="text-[11px] font-bold text-gray-600 mb-0.5 mt-0.5">{{ $employee->created_at->format('M Y') }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Bergabung</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-400 text-sm">Belum ada pegawai.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- ================= PELANGGAN SECTION ================= -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-b border-gray-200 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-brand-dark">Pelanggan</h3>
                <p class="text-[10px] font-bold text-gray-400">{{ $stats['pelanggan'] }} akun terdaftar</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-56">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Cari pelanggan..." class="bg-gray-50 border border-gray-200 text-gray-600 text-xs font-bold rounded-lg focus:border-brand-primary focus:ring-1 focus:ring-brand-primary block w-full pl-9 py-2 transition-all outline-none placeholder-gray-400 shadow-sm">
            </div>
            <div class="relative">
                <select class="appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg pl-4 pr-8 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                    <option>Semua status</option>
                </select>
                
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50/30 text-gray-400 text-[10px] font-extrabold uppercase tracking-wider">
                    <th class="py-3 px-6 whitespace-nowrap">Pelanggan</th>
                    <th class="py-3 px-6 whitespace-nowrap">Kontak</th>
                    <th class="py-3 px-6 whitespace-nowrap">Total Booking</th>
                    <th class="py-3 px-6 whitespace-nowrap">Booking Terakhir</th>
                    <th class="py-3 px-6 whitespace-nowrap">Terdaftar</th>
                    <th class="py-3 px-6 whitespace-nowrap">Status</th>
                    <th class="py-3 px-6 whitespace-nowrap text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold text-gray-600">
                @forelse($customers as $customer)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition cursor-pointer" onclick="openCustomerDetailModal({{ $customer->id }})">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            @php
                                $colors = [
                                    ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                    ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                                    ['bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
                                    ['bg' => 'bg-sky-100', 'text' => 'text-sky-600'],
                                    ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                                    ['bg' => 'bg-teal-100', 'text' => 'text-teal-600'],
                                ];
                                $color = $colors[array_rand($colors)];
                                $initials = collect(explode(' ', $customer->name))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                            @endphp
                            @if($customer->avatar)
                            <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-100 flex items-center justify-center shrink-0 bg-white">
                                <img src="{{ asset('images/profile_akun/' . strtolower($customer->role ?? 'user') . '/' . $customer->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            @else
                            <div class="w-8 h-8 rounded-full {{ $color['bg'] }} {{ $color['text'] }} flex items-center justify-center font-black text-xs shrink-0">{{ strtoupper($initials) }}</div>
                            @endif
                            <span class="text-brand-dark font-extrabold">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-gray-500 mb-0.5">{{ $customer->email }}</p>
                        <p class="text-[10px] text-gray-400">{{ $customer->phone_number ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <span class="text-brand-dark font-extrabold">{{ $customer->bookings()->count() ?? 0 }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">{{ $customer->bookings()->latest()->first()?->created_at->format('M Y') ?? '-' }}</td>
                    <td class="py-4 px-6">{{ $customer->created_at->format('M Y') }}</td>
                    <td class="py-4 px-6">
                        @if($customer->email_verified_at)
                            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-[9px] font-extrabold rounded-full">Belum Verifikasi</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <button onclick="event.stopPropagation(); openCustomerDetailModal({{ $customer->id }})" class="px-4 py-1 bg-white border border-gray-200 text-gray-500 hover:text-brand-dark hover:bg-gray-50 text-[10px] font-extrabold rounded-full transition shadow-sm">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400 text-sm">Belum ada pelanggan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer Pagination -->
    <div class="p-4 border-t border-gray-200">
        {{ $customers->links() }}
    </div>

</div>

<!-- Modal Tambah Pegawai -->
<div id="add-employee-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeAddEmployeeModal()"></div>

    <!-- Modal Content -->
    <div id="add-employee-modal-content" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[480px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col">
        
        <!-- Form -->
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-black text-brand-dark tracking-tight">Tambah Pegawai Baru</h3>
                <button type="button" onclick="closeAddEmployeeModal()" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Foto Profil (Opsional)</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-xl px-4 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Nama lengkap</label>
                        <input type="text" name="name" required placeholder="Krisna Aldi" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Nomer HP</label>
                        <input type="text" name="phone_number" placeholder="082389390943" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" required placeholder="krisnaaldi@gmail.com" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Spesialisasi</label>
                        <div class="relative">
                            <select name="role" required class="w-full appearance-none bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer pr-8">
                                <option value="pegawai">Pegawai</option>
                                <option value="admin">Admin</option>
                            </select>
                            <span class="absolute bottom-3.5 right-3 flex items-center pointer-events-none text-brand-dark">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Password awal</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                    </div>
                </div>

                <div class="bg-blue-50 text-blue-700 text-[10px] font-bold p-3 rounded-lg border border-blue-100 mb-2">
                    Note: Setelah akun berhasil ditambahkan, pegawai dapat langsung Login dan bisa langsung ditugaskan
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeAddEmployeeModal()" class="px-6 py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 text-xs font-extrabold rounded-xl transition flex items-center justify-center">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] flex items-center justify-center gap-2">
                    + Tambah Pegawai
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal Detail Pegawai Loops -->
@foreach($employees->concat($admins) as $employee)
<div id="employee-detail-modal-{{ $employee->id }}" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeEmployeeDetailModal({{ $employee->id }})"></div>

    <!-- Modal Content -->
    <div id="employee-detail-modal-content-{{ $employee->id }}" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[500px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col max-h-[90vh] overflow-y-auto">
        
        <form action="{{ route('admin.users.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-black text-brand-dark tracking-tight">Detail – {{ $employee->name }}</h3>
                <button type="button" onclick="closeEmployeeDetailModal({{ $employee->id }})" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                
                <!-- Profile Info -->
                <div class="flex items-center gap-4 mb-6">
                    @php
                        $initials = collect(explode(' ', $employee->name))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                    @endphp
                    @if($employee->avatar)
                    <div class="w-14 h-14 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center shadow-sm shrink-0 bg-white">
                        <img src="{{ asset('images/profile_akun/' . strtolower($employee->role ?? 'user') . '/' . $employee->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="w-14 h-14 rounded-2xl bg-pink-500 text-white flex items-center justify-center text-xl font-black shadow-sm shrink-0">{{ strtoupper($initials) }}</div>
                    @endif
                    <div>
                        <h4 class="text-base font-black text-brand-dark mb-0.5">{{ $employee->name }}</h4>
                        <p class="text-[11px] font-bold text-gray-400 mb-2">{{ $employee->email }}</p>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-purple-100 text-purple-600 text-[9px] font-extrabold rounded-full">{{ ['pegawai' => 'Pegawai', 'admin' => 'Admin', 'user' => 'Pelanggan'][$employee->role] ?? ucfirst($employee->role) }}</span>
                            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full">Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-4 gap-3 mb-6">
                    <div class="bg-gray-50/50 rounded-xl p-3 border border-gray-100 text-center">
                        <h5 class="text-lg font-black text-brand-dark mb-0.5">{{ $employee->assignments()->count() ?? 0 }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Total sesi</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-xl p-3 border border-gray-100 text-center">
                        <h5 class="text-lg font-black text-brand-dark mb-0.5">{{ $employee->assignments()->whereMonth('created_at', \Carbon\Carbon::now()->month)->count() ?? 0 }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Sesi bulan ini</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-xl p-3 border border-gray-100 text-center">
                        <h5 class="text-xs font-black text-brand-dark mb-1 mt-1">{{ $employee->created_at->format('M Y') }}</h5>
                        <p class="text-[9px] font-bold text-gray-400">Bergabung</p>
                    </div>
                    @php
                        $avgRating = $employee->reviewsReceived->avg('rating') ?? 0;
                        $reviewCount = $employee->reviewsReceived->count();
                    @endphp
                    <div onclick="openReviewModal({{ $employee->id }})" class="bg-yellow-50 rounded-xl p-3 border border-yellow-100 text-center cursor-pointer hover:bg-yellow-100 transition">
                        <h5 class="text-sm font-black text-yellow-600 mb-0.5 mt-0.5 flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            {{ number_format($avgRating, 1) }}
                        </h5>
                        <p class="text-[9px] font-bold text-yellow-700">Lihat ulasan</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Foto Profil (Opsional)</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-xl px-4 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Nama lengkap</label>
                    <input type="text" name="name" value="{{ $employee->name }}" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>
                
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ $employee->email }}" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Nomer HP</label>
                        <input type="text" name="phone_number" value="{{ $employee->phone_number }}" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Password baru (Kosongkan jika tidak ubah)</label>
                        <input type="password" name="password" placeholder="Min 8 Karakter" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                    </div>
                </div>

                <!-- Role Switcher -->
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Ubah Role</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" class="peer sr-only" value="admin" {{ $employee->role == 'admin' ? 'checked' : '' }}>
                            <div class="p-3 bg-white border border-gray-200 rounded-xl peer-checked:border-brand-primary peer-checked:ring-1 peer-checked:ring-brand-primary peer-checked:bg-blue-50/50 transition flex flex-col items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black text-gray-500 peer-checked:text-brand-dark transition">Admin</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" class="peer sr-only" value="pegawai" {{ $employee->role == 'pegawai' ? 'checked' : '' }}>
                            <div class="p-3 bg-white border border-gray-200 rounded-xl peer-checked:border-brand-primary peer-checked:ring-1 peer-checked:ring-brand-primary peer-checked:bg-blue-50/50 transition flex flex-col items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black text-gray-500 peer-checked:text-brand-dark transition">Pegawai</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" class="peer sr-only" value="user" {{ $employee->role == 'user' ? 'checked' : '' }}>
                            <div class="p-3 bg-white border border-gray-200 rounded-xl peer-checked:border-brand-primary peer-checked:ring-1 peer-checked:ring-brand-primary peer-checked:bg-blue-50/50 transition flex flex-col items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black text-gray-500 peer-checked:text-brand-dark transition">Pelanggan</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="p-6 border-t border-gray-100 flex justify-between gap-3 shrink-0">
                <button type="button" onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus pegawai ini?')) document.getElementById('delete-employee-{{ $employee->id }}').submit();" class="px-6 py-2.5 bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 text-xs font-extrabold rounded-xl transition flex items-center justify-center">
                    Hapus akun
                </button>
                <button type="submit" class="px-8 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] flex items-center justify-center">
                    Simpan
                </button>
            </div>
        </form>
        
        <form id="delete-employee-{{ $employee->id }}" action="{{ route('admin.users.destroy', $employee->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

<!-- Modal Ulasan Klien -->
<div id="review-modal-{{ $employee->id }}" class="fixed inset-0 z-[60] flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-brand-dark/50 backdrop-blur-sm transition-opacity" onclick="closeReviewModal({{ $employee->id }})"></div>

    <!-- Modal Content -->
    <div id="review-modal-content-{{ $employee->id }}" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[500px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col max-h-[85vh]">
        
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md rounded-t-[24px] z-20">
            <div>
                <h3 class="text-lg font-black text-brand-dark tracking-tight">Ulasan Klien</h3>
                <p class="text-[11px] font-bold text-gray-400">Menampilkan ulasan dari {{ $employee->name }}</p>
            </div>
            <button onclick="closeReviewModal({{ $employee->id }})" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body Scrollable -->
        <div class="p-6 overflow-y-auto">
            
            <!-- Stats -->
            <div class="flex items-center gap-8 mb-8 px-2">
                <!-- Big Number -->
                <div class="flex flex-col items-center">
                    <h2 class="text-5xl font-black text-brand-dark leading-none mb-2">{{ number_format($avgRating, 1) }}</h2>
                    <div class="flex items-center gap-1 text-yellow-400 mb-1">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400">{{ $reviewCount }} Ulasan</p>
                </div>
                <!-- Rating Bars -->
                <div class="flex-1 flex flex-col gap-2">
                    @php
                        $ratingCounts = $employee->reviewsReceived->groupBy('rating')->map->count();
                        $totalReviews = $reviewCount ?: 1;
                    @endphp
                    @for($star = 5; $star >= 1; $star--)
                    @php $pct = $reviewCount > 0 ? round(($ratingCounts[$star] ?? 0) / $totalReviews * 100) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold text-gray-500 flex items-center gap-1 w-6">{{ $star }} <svg class="w-2.5 h-2.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

                        <!-- Review List -->
            <div class="border-t border-gray-100 pt-6 space-y-6">
                @forelse($employee->reviewsReceived as $review)
                <div class="{{ $loop->first ? '' : 'border-t border-gray-50 pt-6' }}">
                    <div class="flex justify-between items-start mb-1.5">
                        <h4 class="text-xs font-extrabold text-brand-dark">{{ $review->customer->name ?? 'Anonim' }}</h4>
                        <span class="text-[9px] font-bold text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex text-yellow-400 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @else
                                <svg class="w-3 h-3 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endif
                        @endfor
                    </div>
                    <p class="text-[11px] font-bold text-gray-500 leading-relaxed">{{ $review->comment }}</p>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-xs text-gray-400 font-bold">Belum ada ulasan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

            
        </div>

    </div>
</div>


@endforeach

<!-- Modal Detail Pelanggan Loops -->
@foreach($customers as $customer)
<div id="customer-detail-modal-{{ $customer->id }}" class="fixed inset-0 z-[60] flex items-center justify-center hidden opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity" onclick="closeCustomerDetailModal({{ $customer->id }})"></div>
    <div id="customer-detail-modal-content-{{ $customer->id }}" class="bg-white rounded-[24px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] w-full max-w-[500px] transform scale-95 transition-all duration-300 relative z-10 mx-4 border border-gray-50 flex flex-col max-h-[90vh] overflow-y-auto">
        
        <form action="{{ route('admin.users.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-black text-brand-dark tracking-tight">Detail – {{ $customer->name }}</h3>
                <button type="button" onclick="closeCustomerDetailModal({{ $customer->id }})" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <!-- Profile Info -->
                <div class="flex items-center gap-4 mb-6">
                    @php
                        $initials = collect(explode(' ', $customer->name))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                    @endphp
                    @if($customer->avatar)
                    <div class="w-14 h-14 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center shadow-sm shrink-0 bg-white">
                        <img src="{{ asset('images/profile_akun/' . strtolower($customer->role ?? 'user') . '/' . $customer->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-black shadow-sm shrink-0">{{ strtoupper($initials) }}</div>
                    @endif
                    <div>
                        <h4 class="text-base font-black text-brand-dark mb-0.5">{{ $customer->name }}</h4>
                        <p class="text-[11px] font-bold text-gray-400">{{ $customer->email }}</p>
                        <p class="text-[11px] font-bold text-gray-400">{{ $customer->phone ?? '-' }}</p>
                    </div>
                </div>

                <!-- Role Switcher -->
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Ubah Role</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" class="peer sr-only" value="admin" {{ $customer->role == 'admin' ? 'checked' : '' }}>
                            <div class="p-3 bg-white border border-gray-200 rounded-xl peer-checked:border-brand-primary peer-checked:ring-1 peer-checked:ring-brand-primary peer-checked:bg-blue-50/50 transition flex flex-col items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black text-gray-500 peer-checked:text-brand-dark transition">Admin</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" class="peer sr-only" value="pegawai" {{ $customer->role == 'pegawai' ? 'checked' : '' }}>
                            <div class="p-3 bg-white border border-gray-200 rounded-xl peer-checked:border-brand-primary peer-checked:ring-1 peer-checked:ring-brand-primary peer-checked:bg-blue-50/50 transition flex flex-col items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black text-gray-500 peer-checked:text-brand-dark transition">Pegawai</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" class="peer sr-only" value="user" {{ $customer->role == 'user' ? 'checked' : '' }}>
                            <div class="p-3 bg-white border border-gray-200 rounded-xl peer-checked:border-brand-primary peer-checked:ring-1 peer-checked:ring-brand-primary peer-checked:bg-blue-50/50 transition flex flex-col items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black text-gray-500 peer-checked:text-brand-dark transition">Pelanggan</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Foto Profil (Opsional)</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-xl px-4 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>
                
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Nama lengkap</label>
                    <input type="text" name="name" value="{{ $customer->name }}" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>
                
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ $customer->email }}" required class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Total booking</label>
                        <div class="w-full bg-gray-50 border border-gray-100 text-brand-dark text-xs font-bold rounded-xl px-4 py-3">{{ $customer->bookings()->count() ?? 0 }}</div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Total bayar selama di Imako</label>
                        <div class="w-full bg-gray-50 border border-gray-100 text-brand-dark text-xs font-bold rounded-xl px-4 py-3">Rp {{ number_format($customer->payments()->where('payments.status', 'verified')->sum('payments.amount') ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Bergabung sejak</label>
                        <div class="w-full bg-gray-50 border border-gray-100 text-brand-dark text-xs font-bold rounded-xl px-4 py-3">{{ $customer->created_at->format('M Y') }}</div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Reset password</label>
                        <input type="password" name="password" placeholder="Minimal 8 angka" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Status</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" id="btn-status-customer-aktif-{{ $customer->id }}" class="py-2.5 bg-green-100 text-green-700 border border-green-200 text-xs font-extrabold rounded-xl transition">Aktif</button>
                        <button type="button" id="btn-status-customer-nonaktif-{{ $customer->id }}" class="py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 text-xs font-extrabold rounded-xl transition">Nonaktif</button>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Alasan dinonaktifkan</label>
                    <input type="text" placeholder="Contoh: Akun fiktif" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition placeholder-gray-300">
                </div>

            </div>

            <div class="p-6 border-t border-gray-100 flex justify-between gap-3 shrink-0">
                <button type="button" onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus pelanggan ini?')) document.getElementById('delete-customer-{{ $customer->id }}').submit();" class="px-6 py-2.5 bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 text-xs font-extrabold rounded-xl transition flex items-center justify-center">
                    Hapus akun
                </button>
                <button type="submit" class="px-8 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] flex items-center justify-center">
                    Simpan
                </button>
            </div>
        </form>
        
        <form id="delete-customer-{{ $customer->id }}" action="{{ route('admin.users.destroy', $customer->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

@endforeach

@endsection

@push('scripts')
<script>
    function openAddEmployeeModal(role = 'pegawai') {
        const modal = document.getElementById('add-employee-modal');
        const content = document.getElementById('add-employee-modal-content');
        
        const roleSelect = document.querySelector('#add-employee-modal select[name="role"]');
        if(roleSelect) roleSelect.value = role;

        modal.classList.remove('hidden');
        void modal.offsetWidth; // trigger reflow
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'translate-y-4');
        content.classList.add('scale-100', 'translate-y-0');
    }

    function closeAddEmployeeModal() {
        const modal = document.getElementById('add-employee-modal');
        const content = document.getElementById('add-employee-modal-content');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-4');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
    function openReviewModal(id) {
        // close the detail modal first to avoid overlap (optional, but cleaner)
        const detailModal = document.getElementById('employee-detail-modal-' + id);
        if(detailModal) {
            detailModal.classList.add('opacity-0');
            setTimeout(() => {
                detailModal.classList.add('hidden');
            }, 300);
        }

        const modal = document.getElementById('review-modal-' + id);
        const content = document.getElementById('review-modal-content-' + id);
        
        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }

    function closeReviewModal(id) {
        const modal = document.getElementById('review-modal-' + id);
        const content = document.getElementById('review-modal-content-' + id);
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            
            // Re-open the detail modal
            openEmployeeDetailModal(id);
        }, 300);
    }

    function openEmployeeDetailModal(id) {
        const modal = document.getElementById('employee-detail-modal-' + id);
        const content = document.getElementById('employee-detail-modal-content-' + id);
        if (modal && content) {
            modal.classList.remove('hidden');
            void modal.offsetWidth; // trigger reflow
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'translate-y-4');
            content.classList.add('scale-100', 'translate-y-0');
        }
    }

    function closeEmployeeDetailModal(id) {
        const modal = document.getElementById('employee-detail-modal-' + id);
        const content = document.getElementById('employee-detail-modal-content-' + id);
        if (modal && content) {
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-4');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }
    

    function toggleEmployeeStatus(status) {
        const btnAktif = document.getElementById('btn-status-aktif');
        const btnNonaktif = document.getElementById('btn-status-nonaktif');

        if (status === 'aktif') {
            // Set Aktif to active state
            btnAktif.className = "py-2.5 bg-green-100 text-green-700 border border-green-200 text-xs font-extrabold rounded-xl transition";
            // Set Nonaktif to inactive state
            btnNonaktif.className = "py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 text-xs font-extrabold rounded-xl transition";
        } else {
            // Set Aktif to inactive state
            btnAktif.className = "py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 text-xs font-extrabold rounded-xl transition";
            // Set Nonaktif to active state (maybe gray-200 or red-50 to indicate inactive account)
            btnNonaktif.className = "py-2.5 bg-red-50 text-red-600 border border-red-200 text-xs font-extrabold rounded-xl transition";
        }
    }

    function toggleCustomerStatus(status) {
        const btnAktif = document.getElementById('btn-status-customer-aktif');
        const btnNonaktif = document.getElementById('btn-status-customer-nonaktif');

        if (status === 'aktif') {
            btnAktif.className = "py-2.5 bg-green-100 text-green-700 border border-green-200 text-xs font-extrabold rounded-xl transition";
            btnNonaktif.className = "py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 text-xs font-extrabold rounded-xl transition";
        } else {
            btnAktif.className = "py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 text-xs font-extrabold rounded-xl transition";
            btnNonaktif.className = "py-2.5 bg-red-50 text-red-600 border border-red-200 text-xs font-extrabold rounded-xl transition";
        }
    }

    function openCustomerDetailModal(id) {
        const modal = document.getElementById('customer-detail-modal-' + id);
        const content = document.getElementById('customer-detail-modal-content-' + id);
        if (modal && content) {
            modal.classList.remove('hidden');
            void modal.offsetWidth; // trigger reflow
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'translate-y-4');
            content.classList.add('scale-100', 'translate-y-0');
        }
    }

    function closeCustomerDetailModal(id) {
        const modal = document.getElementById('customer-detail-modal-' + id);
        const content = document.getElementById('customer-detail-modal-content-' + id);
        if (modal && content) {
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-4');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }
</script>
@endpush
