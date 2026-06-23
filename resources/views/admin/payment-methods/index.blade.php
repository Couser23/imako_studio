@extends('layouts.admin')

@section('title', 'Metode Pembayaran')
@section('pre-title', 'Pengaturan Keuangan')
@section('subtitle', 'Kelola opsi metode pembayaran yang tersedia')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-end gap-4">
    <a href="{{ route('admin.payment-methods.create') }}" class="px-6 py-3 bg-brand-dark hover:bg-brand-primary text-white text-sm font-bold rounded-2xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)] flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        <span>Tambah Metode</span>
    </a>
</div>

<!-- Main Section -->
<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 text-xs font-black uppercase tracking-wider">
                    <th class="py-5 px-8 whitespace-nowrap">Nama Metode</th>
                    <th class="py-5 px-8 whitespace-nowrap">Nomor Rekening</th>
                    <th class="py-5 px-8 whitespace-nowrap">Atas Nama</th>
                    <th class="py-5 px-8 whitespace-nowrap text-center">QR / Gambar</th>
                    <th class="py-5 px-8 whitespace-nowrap text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm font-bold text-gray-600">
                @forelse($paymentMethods as $paymentMethod)
                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors group">
                    <td class="py-6 px-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xl shadow-inner">
                                {{ strtoupper(substr($paymentMethod->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-brand-dark font-extrabold text-base mb-0.5">{{ $paymentMethod->name }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">
                                    @if($paymentMethod->provider == 'transfer')
                                        Transfer Bank / E-Wallet
                                    @elseif($paymentMethod->provider == 'qris')
                                        QRIS
                                    @elseif($paymentMethod->provider == 'cash')
                                        Cash (Tunai)
                                    @else
                                        {{ $paymentMethod->provider }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="py-6 px-8 text-brand-dark font-black tracking-widest text-lg">{{ $paymentMethod->account_number }}</td>
                    <td class="py-6 px-8 text-gray-500">{{ $paymentMethod->account_name }}</td>
                    <td class="py-6 px-8 text-center">
                        @if($paymentMethod->logo)
                            <div class="flex justify-center">
                                <img src="{{ asset('images/metode_pembayaran/' . $paymentMethod->logo) }}" alt="QR" class="h-16 w-16 object-contain rounded-md shadow-sm border border-gray-200">
                            </div>
                        @else
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-400 text-[10px] rounded-full font-bold">Tanpa Logo/QR</span>
                        @endif
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.payment-methods.edit', $paymentMethod->id) }}" class="w-9 h-9 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition shadow-sm border border-yellow-100 hover:border-transparent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <form action="{{ route('admin.payment-methods.destroy', $paymentMethod->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus metode pembayaran ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition shadow-sm border border-red-100 hover:border-transparent">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div> 
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-500 font-medium">Belum ada metode pembayaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-100">
        {{ $paymentMethods->links() }}
    </div>

</div>
@endsection
