@extends('layouts.admin')

@section('title', 'Kelola Paket')
@section('pre-title', 'Paket & Layanan')
@section('subtitle', 'Daftar paket foto dan video yang tersedia')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <!-- <h2 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Kelola Paket Layanan</h2> -->
        <!-- <p class="text-sm font-medium text-gray-500">Atur dan kelola daftar harga serta paket pemotretan Anda.</p> -->
    </div>
    
    <div class="flex flex-col sm:flex-row items-center gap-3">
        <!-- Search Input -->
        <div class="relative w-full sm:w-60">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" placeholder="Cari nama paket..." class="bg-white border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] text-gray-800 text-sm font-medium rounded-2xl focus:border-brand-primary focus:ring-2 focus:ring-inset focus:ring-brand-primary block w-full pl-11 py-3 transition-all outline-none">
        </div>

        <!-- Add Button -->
        <a href="{{ route('admin.packages.create') }}" class="w-full sm:w-auto px-6 py-3 bg-brand-dark hover:bg-brand-primary text-white text-sm font-bold rounded-2xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)] flex items-center justify-center gap-2 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Paket</span>
        </a>
    </div>
</div>

<!-- Modern Filter Tabs -->
<div class="flex overflow-x-auto pb-4 mb-6 hide-scrollbar gap-2">
    <a href="{{ route('admin.packages.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold {{ !request('category') ? 'bg-brand-dark text-white shadow-md' : 'bg-white text-gray-500 hover:text-brand-dark hover:bg-gray-50 shadow-sm border border-gray-100' }} transition whitespace-nowrap">
        Semua Kategori
    </a>
    @foreach($categories as $category)
        <a href="{{ route('admin.packages.index', ['category' => $category->slug]) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold {{ request('category') === $category->slug ? 'bg-brand-dark text-white shadow-md' : 'bg-white text-gray-500 hover:text-brand-dark hover:bg-gray-50 shadow-sm border border-gray-100' }} transition whitespace-nowrap">
            {{ $category->name }}
        </a>
    @endforeach
</div>

<!-- Elegant Image Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
    
    @forelse($packages as $package)
    <!-- Package Card -->
    <div class="group bg-white rounded-[24px] overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(0,0,0,0.08)] transition-all duration-300 relative border border-gray-50 flex flex-col">
        <!-- Badges -->
        <div class="absolute top-4 left-4 z-10 flex flex-wrap gap-2 max-w-[80%]">
            <span class="bg-white/90 backdrop-blur {{ $package->is_active ? 'text-brand-dark' : 'text-red-500' }} px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">
                {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
            @if(isset($hotSalePackageIds) && in_array($package->id, $hotSalePackageIds))
            <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-md shadow-red-500/20 flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                Hot Sale
            </span>
            @endif
            @if($package->tag === 'premium')
            <span class="bg-gray-900 text-amber-300 border border-gray-700/50 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-gray-900/20 backdrop-blur-md flex items-center gap-1.5">
                <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Premium
            </span>
            @elseif($package->tag === 'luxury')
            <span class="bg-blue-900 text-cyan-300 border border-blue-800/50 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 backdrop-blur-md flex items-center gap-1.5">
                <svg class="w-3 h-3 text-cyan-400" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Luxury
            </span>
            @endif
            @if($package->discount_price)
            <span class="bg-red-500 text-white border border-red-400/50 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-500/20 backdrop-blur-md flex items-center gap-1.5">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                Diskon
            </span>
            @endif
        </div>
        <!-- Action Overlay -->
        <div class="absolute top-4 right-4 z-10 flex gap-2 opacity-0 group-hover:opacity-100 transition duration-300 translate-y-2 group-hover:translate-y-0">
            <a href="{{ route('admin.packages.edit', $package->id) }}" class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-600 hover:text-brand-primary shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </a>
            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 hover:text-red-700 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
        <!-- Image Area -->
        <div class="relative w-full aspect-[4/5] overflow-hidden bg-gray-100">
            @if($package->image)
                <img src="{{ asset('images/paket/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">No Image</div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
        </div>
        <!-- Content Area -->
        <div class="p-5 flex flex-col flex-1 bg-white relative">
            <div class="flex justify-between items-start mb-1">
                <h3 class="text-base font-black text-brand-dark leading-tight group-hover:text-brand-primary transition">{{ $package->name }}</h3>
            </div>
            <p class="text-xs font-medium text-gray-400 mb-4 line-clamp-2">{{ $package->description }}</p>
            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                @if($package->discount_price)
                <div class="flex flex-col">
                    <p class="text-[10px] font-bold text-gray-400 line-through decoration-red-500/50">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    <p class="text-base font-black text-red-500">Rp {{ number_format($package->discount_price, 0, ',', '.') }}</p>
                </div>
                @else
                <p class="text-sm font-black text-brand-primary">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                @endif
                <span class="flex items-center text-[10px] font-bold text-gray-400"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $package->duration_minutes }} Menit</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-gray-500 font-medium">
        Belum ada paket layanan. Silakan tambah paket baru.
    </div>
    @endforelse

</div>

<div class="mt-6">
    {{ $packages->links() }}
</div>

</div>

<!-- Styles for hide-scrollbar -->
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
