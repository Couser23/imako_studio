@extends('layouts.user', ['title' => 'Katalog Paket'])

@section('content')
<!-- <div class="mb-6 -mt-4">
    <p class="text-sm text-gray-400 font-medium">Tentukan paket yang akan Anda pilih sebelum booking</p>
</div> -->

@php
    $mappedPackages = $packages->map(function($pkg) {
        $benefits = [];
        if ($pkg->description) {
            $lines = explode("\n", str_replace("\r", "", $pkg->description));
            $benefits = array_values(array_filter(array_map('trim', $lines)));
        } else {
            $benefits = [$pkg->duration_minutes . ' menit sesi foto'];
        }
        
        return [
            'id' => $pkg->id,
            'name' => $pkg->name,
            'price' => (int) $pkg->price,
            'priceDisplay' => 'Rp ' . number_format($pkg->price, 0, ',', '.'),
            'image' => $pkg->image ? asset('images/paket/' . $pkg->image) : 'https://ui-avatars.com/api/?name=' . urlencode($pkg->name) . '&background=F1F5F9&color=2B5488&size=512',
            'category' => $pkg->category ? $pkg->category->name : 'Lainnya',
            'isNew' => in_array(strtolower($pkg->tag), ['baru!', 'new', 'hot', 'promo']),
            'benefits' => $benefits
        ];
    })->values()->toArray();
@endphp

<div class="w-full" x-data="catalogData()">
    <!-- Hero Header -->
    <div class="relative bg-gradient-to-br from-brand-dark via-brand-primary to-blue-600 rounded-[24px] p-8 md:p-10 text-white mb-8 shadow-lg z-20">
        
        <!-- Decorative Background Elements (Clipped) -->
        <div class="absolute inset-0 rounded-[24px] overflow-hidden pointer-events-none">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] mix-blend-overlay"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute right-40 -bottom-20 w-48 h-48 bg-blue-400/20 rounded-full blur-2xl"></div>
        </div>
        
        <div class="relative z-30">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3">Katalog Paket</h2>
            <p class="text-blue-100 text-sm md:text-base max-w-2xl font-medium mb-8">Eksplorasi berbagai pilihan paket foto berkualitas tinggi untuk mengabadikan momen spesial Anda secara sempurna.</p>
            
            <div class="flex flex-col sm:flex-row gap-4 items-stretch">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input x-model="searchQuery" type="text" placeholder="Cari nama paket..." class="w-full pl-11 pr-4 py-3.5 bg-white rounded-xl text-gray-800 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-white/20 shadow-lg placeholder-gray-400 transition-all h-full border-0">
                </div>
                
                <div class="relative h-full">
                    <button @click="filterMenuOpen = !filterMenuOpen" @click.away="filterMenuOpen = false" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-white text-sm font-bold transition flex items-center justify-between gap-3 border border-white/20 w-full sm:w-auto h-full shadow-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span x-text="sortOrder === 'default' ? 'Urutkan Harga' : (sortOrder === 'termurah' ? 'Termurah - Termahal' : 'Termahal - Termurah')"></span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="filterMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="filterMenuOpen" x-transition x-cloak class="absolute right-0 sm:left-0 mt-2 w-full sm:w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-20" style="display: none;">
                        <button @click="sortOrder = 'default'; filterMenuOpen = false" :class="sortOrder === 'default' ? 'text-brand-blue bg-blue-50/80 font-bold border-l-2 border-brand-blue' : 'text-gray-600 font-semibold border-l-2 border-transparent'" class="w-full text-left px-5 py-2.5 text-sm hover:bg-gray-50 transition">Urutan Default</button>
                        <button @click="sortOrder = 'termurah'; filterMenuOpen = false" :class="sortOrder === 'termurah' ? 'text-brand-blue bg-blue-50/80 font-bold border-l-2 border-brand-blue' : 'text-gray-600 font-semibold border-l-2 border-transparent'" class="w-full text-left px-5 py-2.5 text-sm hover:bg-gray-50 transition">Termurah ke Termahal</button>
                        <button @click="sortOrder = 'termahal'; filterMenuOpen = false" :class="sortOrder === 'termahal' ? 'text-brand-blue bg-blue-50/80 font-bold border-l-2 border-brand-blue' : 'text-gray-600 font-semibold border-l-2 border-transparent'" class="w-full text-left px-5 py-2.5 text-sm hover:bg-gray-50 transition">Termahal ke Termurah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="flex flex-wrap items-center gap-2.5 mb-8">
        <button @click="filter = 'Semua'" :class="filter === 'Semua' ? 'bg-brand-dark text-white shadow-md' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'" class="px-5 py-2 text-sm font-extrabold rounded-xl transition-all duration-200">Semua Kategori</button>
        @foreach($categories as $cat)
            <button @click="filter = '{{ $cat->name }}'" :class="filter === '{{ $cat->name }}' ? 'bg-brand-dark text-white shadow-md' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'" class="px-5 py-2 text-sm font-extrabold rounded-xl transition-all duration-200">{{ $cat->name }}</button>
        @endforeach
    </div>

    <!-- Package Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        <template x-for="pkg in filteredPackages" :key="pkg.name">
            <div @click="openModal(pkg)" class="group cursor-pointer bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 dark:border-slate-700/60 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                <div class="relative w-full aspect-[4/5] bg-gray-100 dark:bg-slate-700 overflow-hidden shrink-0">
                    <img :src="pkg.image" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out">
                    
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300"></div>
                    
                    <!-- New Badge -->
                    <div x-show="pkg.isNew" x-cloak class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border border-red-400">HOT</div>

                    <!-- Category Tag -->
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md text-white border border-white/30 text-[10px] font-bold uppercase px-3 py-1 rounded-full shadow-lg" x-text="pkg.category"></div>

                    <!-- Content at Bottom -->
                    <div class="absolute bottom-0 left-0 w-full p-5 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-lg font-extrabold text-white leading-tight mb-2" x-text="pkg.name"></h3>
                        <div class="inline-block bg-white text-brand-dark px-3 py-1.5 rounded-lg text-sm font-black shadow-lg" x-text="pkg.priceDisplay"></div>
                        
                        <!-- Expandable Benefit preview -->
                        <div class="mt-4 h-0 opacity-0 group-hover:h-auto group-hover:opacity-100 transition-all duration-300 overflow-hidden">
                            <div class="flex items-start gap-2 text-blue-50 text-[12px] font-medium border-t border-white/20 pt-3">
                                <svg class="w-4 h-4 shrink-0 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="line-clamp-2 leading-snug" x-text="pkg.benefits.length > 0 ? pkg.benefits[0] : 'Sesi foto eksklusif'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-cloak style="display: none;" x-show="filteredPackages.length === 0" class="col-span-full flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-300 dark:border-slate-700">
            <div class="w-20 h-20 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-1">Paket tidak ditemukan</h3>
            <p class="text-sm font-medium text-gray-500">Silakan coba kata kunci pencarian yang lain.</p>
        </div>
        
    </div>

    <!-- Detail Paket Modal -->
    <template x-teleport="body">
        <div x-show="detailModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
            <!-- Backdrop -->
            <div x-show="detailModal" x-transition.opacity @click="detailModal = false" class="absolute inset-0 bg-brand-dark/60 backdrop-blur-md"></div>
        
        <!-- Modal Panel -->
        <div x-show="detailModal" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-8" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
             x-transition:leave-end="opacity-0 scale-95 translate-y-8" 
             class="relative bg-white dark:bg-slate-800 rounded-[24px] shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col">
            
            <!-- Image Header -->
            <div class="relative h-56 w-full bg-gray-200 dark:bg-slate-700 shrink-0">
                <img :src="pkgImage" :alt="pkgName" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-slate-800 to-transparent opacity-90"></div>
                
                <button @click="detailModal = false" class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center bg-black/20 hover:bg-black/40 text-white backdrop-blur-md rounded-full transition cursor-pointer z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Content Area (Scrollable) -->
            <div class="px-8 pb-8 -mt-16 relative overflow-y-auto flex-1 custom-scrollbar">
                <!-- Price Badge Floating -->
                <div class="absolute right-8 top-0 bg-gradient-to-r from-brand-blue to-blue-600 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-600/30 font-black text-base" x-text="pkgPrice"></div>
                
                <div class="pt-2 mb-6 w-3/4">
                    <span class="inline-block bg-blue-50 dark:bg-slate-700 text-brand-blue dark:text-blue-400 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest mb-3" x-text="pkgCategory"></span>
                    <h3 class="font-black text-brand-dark dark:text-white text-2xl leading-tight" x-text="pkgName"></h3>
                </div>
                
                <div class="bg-gray-50 dark:bg-slate-700/50 border border-gray-100 dark:border-slate-600 rounded-2xl p-5 mb-8">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-200 dark:border-slate-600">
                        <svg class="w-5 h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Benefit Layanan</h4>
                    </div>
                    
                    <div class="flex flex-col gap-3.5">
                        <template x-for="benefit in pkgBenefits" :key="benefit">
                            <div class="flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-snug" x-text="benefit"></span>
                            </div>
                        </template>
                    </div>
                </div>
                
                <a :href="'{{ route('user.bookings.create') }}?package=' + pkgId" class="block w-full py-4 bg-brand-dark hover:bg-brand-blue text-white text-center rounded-xl font-bold transition shadow-lg shadow-brand-dark/20 flex items-center justify-center gap-2">
                    <span>Booking Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
    </template>
</div>

<script>
    function catalogData() {
        return {
            filter: 'Semua', 
            searchQuery: @json(request()->query('search', '')),
            sortOrder: 'default',
            filterMenuOpen: false,
            detailModal: false,
            pkgId: null,
            pkgName: '',
            pkgPrice: '',
            pkgBenefits: [],
            pkgImage: '',
            pkgCategory: '',
            packages: @json($mappedPackages),
            openModal(pkg) {
                this.pkgId = pkg.id;
                this.pkgName = pkg.name;
                this.pkgPrice = pkg.priceDisplay;
                this.pkgBenefits = pkg.benefits;
                this.pkgImage = pkg.image;
                this.pkgCategory = pkg.category;
                this.detailModal = true;
            },
            get filteredPackages() {
                let result = this.packages;
                if (this.filter !== 'Semua') {
                    result = result.filter(p => p.category === this.filter);
                }
                if (this.searchQuery.trim() !== '') {
                    let search = this.searchQuery.toLowerCase().replace(/weeding/g, 'wedding').replace(/[-\s]/g, '');
                    result = result.filter(p => p.name.toLowerCase().replace(/weeding/g, 'wedding').replace(/[-\s]/g, '').includes(search));
                }
                if (this.sortOrder === 'termurah') {
                    result = result.slice().sort((a, b) => a.price - b.price);
                } else if (this.sortOrder === 'termahal') {
                    result = result.slice().sort((a, b) => b.price - a.price);
                }
                return result;
            }
        };
    }
</script>
@endsection
