@extends('layouts.admin')

@section('title', 'Pengajuan Libur')
@section('pre-title', 'Admin Panel')
@section('subtitle', 'Kelola pengajuan libur pegawai')

@section('content')
<div class="bg-white rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-sm font-extrabold text-brand-dark">Daftar Pengajuan Libur</h2>
        <form method="GET" action="{{ route('admin.leave-requests.index') }}" class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai..." class="bg-gray-50 border border-gray-200 text-gray-600 text-xs font-bold rounded-lg focus:border-brand-primary focus:ring-1 focus:ring-brand-primary block w-full pl-9 py-1.5 transition-all outline-none placeholder-gray-400">
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Pegawai</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Libur</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Berapa Hari</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leaveRequests as $request)
                <tr class="hover:bg-gray-50/50 transition {{ $request->status !== 'pending' ? 'bg-gray-50/30' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-xs font-extrabold text-brand-dark">{{ $request->user->name ?? 'Pegawai' }}</p>
                        <p class="text-[10px] font-semibold text-gray-400">{{ ['pegawai' => 'Pegawai', 'admin' => 'Admin', 'user' => 'Pelanggan'][$request->user->role ?? 'pegawai'] ?? ucfirst($request->user->role ?? 'pegawai') }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-600">
                        @if($request->start_date == $request->end_date)
                            {{ \Carbon\Carbon::parse($request->start_date)->translatedFormat('d M Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($request->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->translatedFormat('d M Y') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-600">
                        {{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} Hari
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-500">
                        {{ $request->reason ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($request->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-600">
                                Menunggu
                            </span>
                        @elseif($request->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-green-50 text-green-600">
                                Disetujui
                            </span>
                        @elseif($request->status == 'rejected')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-red-50 text-red-500">
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-gray-50 text-gray-500">
                                {{ ucfirst($request->status) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        @if($request->status == 'pending')
                        <div class="flex items-center justify-end gap-2">
                            <!-- Approve Form -->
                            <form action="{{ route('admin.leave-requests.update-status', $request->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-[10px] font-extrabold rounded-lg transition shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Setujui
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ route('admin.leave-requests.update-status', $request->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 text-[10px] font-extrabold rounded-lg transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="flex items-center justify-end gap-2">
                            <span class="text-[10px] font-bold text-gray-400 mr-1">Sudah diproses</span>
                            <form action="{{ route('admin.leave-requests.destroy', $request->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data pengajuan libur ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded-lg transition" title="Hapus Pengajuan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm font-medium">Tidak ada pengajuan libur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
