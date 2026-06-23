@extends('layouts.admin')

@section('title', 'Pembukuan')
@section('pre-title', 'Keuangan')
@section('subtitle', 'Ringkasan pemasukan dan pengeluaran studio')

@section('content')
<!-- <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <h2 class="text-3xl font-extrabold text-brand-dark tracking-tight">Pembukuan</h2>
</div> -->

<!-- Controls Row -->
<div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
    <div class="flex flex-wrap items-center gap-3">
        <!-- Tabs -->
        <div class="flex items-center gap-2 bg-white rounded-lg p-1 shadow-sm border border-gray-100">
            <button id="tab-bulanan-btn" onclick="switchTab('bulanan')" class="px-5 py-2 rounded-md text-sm font-bold bg-gray-200 text-brand-dark transition">Bulanan</button>
            <button id="tab-tahunan-btn" onclick="switchTab('tahunan')" class="px-5 py-2 rounded-md text-sm font-bold bg-transparent text-gray-500 hover:text-brand-dark transition">Tahunan</button>
        </div>

        <!-- Period Selector (Bulanan) -->
        <div id="period-bulanan" class="flex items-center gap-3">
            <a href="{{ route('admin.finance.index', ['month' => $dateContext->copy()->subMonth()->month, 'year' => $dateContext->copy()->subMonth()->year, 'tab' => 'bulanan']) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-200 hover:bg-gray-300 text-brand-dark font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="px-4 py-1.5 bg-blue-50 text-brand-dark font-bold rounded-lg text-sm border border-blue-100">{{ $dateContext->translatedFormat('F Y') }}</div>
            <a href="{{ route('admin.finance.index', ['month' => $dateContext->copy()->addMonth()->month, 'year' => $dateContext->copy()->addMonth()->year, 'tab' => 'bulanan']) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-200 hover:bg-gray-300 text-brand-dark font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <span class="text-brand-dark font-bold text-sm ml-2">vs {{ $dateContext->copy()->subMonth()->translatedFormat('F') }}</span>
        </div>

        <!-- Period Selector (Tahunan) -->
        <div id="period-tahunan" class="hidden items-center gap-3">
            <a href="{{ route('admin.finance.index', ['month' => $dateContext->month, 'year' => $year - 1, 'tab' => 'tahunan']) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-200 hover:bg-gray-300 text-brand-dark font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="px-4 py-1.5 bg-blue-50 text-brand-dark font-bold rounded-lg text-sm border border-blue-100">{{ $year }}</div>
            <a href="{{ route('admin.finance.index', ['month' => $dateContext->month, 'year' => $year + 1, 'tab' => 'tahunan']) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-200 hover:bg-gray-300 text-brand-dark font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <span class="text-brand-dark font-bold text-sm ml-2">vs {{ $year - 1 }}</span>
        </div>
    </div>

    <!-- Cetak Button -->
    <a id="btn-cetak-header" href="{{ route('admin.finance.print', ['type' => request('tab', 'bulanan') == 'tahunan' ? 'yearly' : 'monthly', 'month' => $dateContext->month, 'year' => $year]) }}" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-brand-dark rounded-lg font-bold text-sm flex items-center gap-2 transition border border-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak
    </a>
</div>


<!-- ================= BULANAN VIEW ================= -->
<div id="view-bulanan" class="space-y-6">
    
    <!-- 5 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Total Pemasukan</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold text-green-500">Bulan ini</p>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Hari aktif</p>
                <h3 class="text-base font-black text-brand-dark">{{ $activeDays }} Hari</h3>
                <p class="text-[9px] font-bold text-gray-400">Ada Transaksi</p>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Rata-rata/hari</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format($avgPerDay, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold text-gray-400">Per hari aktif</p>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-white shrink-0"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-brand-primary">Hari terbaik bulan ini</p>
                <h3 class="text-base font-black text-brand-dark">Tanggal {{ $bestDay }}</h3>
                <p class="text-[9px] font-bold text-gray-400">Rp {{ number_format($bestDayAmount, 0, ',', '.') }}</p>
            </div>
        </div>
        <!-- Card 5 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Uang keluar Bulan ini</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Grid Layout Bulanan -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        <!-- Bar Chart (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50 flex flex-col">
            <h3 class="text-lg font-extrabold text-brand-dark mb-4">Pemasukan perhari - {{ $dateContext->translatedFormat('F Y') }}</h3>
            <h2 class="text-2xl font-black text-brand-dark">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h2>
            <p class="text-[10px] font-bold {{ $growth >= 0 ? 'text-green-500' : 'text-red-500' }} mb-6">{{ $growth >= 0 ? '+' : '' }}{{ round($growth, 1) }}% vs bulan lalu</p>
            
            <!-- Simulated Bar Chart -->
            <div class="flex-1 grid gap-1 mt-auto h-40 items-end" style="grid-template-columns: repeat({{ $daysInMonth }}, minmax(0, 1fr));">
                @php
                    $maxDailyRev = max($dailyRevenue) ?: 1;
                @endphp
                @for($i=1; $i<=$daysInMonth; $i++)
                    @php 
                        $rev = $dailyRevenue[$i] ?? 0;
                        $height = ($rev / $maxDailyRev) * 100;
                        if ($height < 2) $height = 2; // min visibility
                        $color = ($i == $bestDay) ? 'bg-brand-primary' : 'bg-gray-300';
                    @endphp
                    <div class="w-full h-full flex flex-col justify-end group" title="Tanggal {{ $i }}: Rp {{ number_format($rev, 0, ',', '.') }}">
                        <div class="w-full {{ $color }} rounded-t-sm transition-all duration-300 group-hover:opacity-80" style="height: {{ $height }}%"></div>
                    </div>
                @endfor
            </div>
            <!-- X Axis -->
            <div class="grid gap-1 mt-2" style="grid-template-columns: repeat({{ $daysInMonth }}, minmax(0, 1fr));">
                @for($i=1; $i<=$daysInMonth; $i++)
                    <div class="w-full flex justify-center text-[8px] font-bold text-gray-400">
                        <span>{{ in_array($i, [1, 5, 10, 15, 20, 25, $daysInMonth]) ? $i : '' }}</span>
                    </div>
                @endfor
            </div>
            <!-- Legend -->
            <div class="flex items-center gap-4 mt-4 text-[10px] font-bold">
                <div class="flex items-center gap-1"><div class="w-3 h-1 bg-brand-primary rounded-full"></div> Hari Terbaik</div>
                <div class="flex items-center gap-1"><div class="w-3 h-1 bg-gray-300 rounded-full"></div> Reguler</div>
            </div>
        </div>

        <!-- Progress Bars (Span 3) -->
        <div class="xl:col-span-3 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-brand-dark mb-4">Pemasukan per-minggu</h3>
                <div class="space-y-3">
                    @php
                        $maxWeeklyRev = max($weeklyRevenue) ?: 1;
                    @endphp
                    @foreach($weeklyRevenue as $weekName => $rev)
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="w-16">{{ $weekName }}</span>
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full mx-2"><div class="h-full bg-brand-primary rounded-full" style="width: {{ ($rev / $maxWeeklyRev) * 100 }}%"></div></div>
                            <span class="w-14 text-right truncate">Rp {{ number_format($rev, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-6">
                <h3 class="text-[10px] font-bold text-brand-dark mb-4">Per paket bulan ini</h3>
                <div class="space-y-3">
                    @php
                        $maxPackageRev = $packageRevenue->max('total') ?: 1;
                        $colors = ['bg-brand-primary', 'bg-green-500', 'bg-orange-400', 'bg-purple-500', 'bg-blue-400'];
                    @endphp
                    @forelse($packageRevenue->take(4) as $idx => $pkg)
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="w-16 truncate" title="{{ $pkg['name'] }}">{{ $pkg['name'] }}</span>
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full mx-2"><div class="h-full {{ $colors[$idx % count($colors)] }} rounded-full" style="width: {{ ($pkg['total'] / $maxPackageRev) * 100 }}%"></div></div>
                            <span class="w-14 text-right truncate" title="Rp {{ number_format($pkg['total'], 0, ',', '.') }}">Rp {{ number_format($pkg['total'] / 1000000, 1, ',', '.') }}jt</span>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs">Belum ada pemasukan paket.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pengeluaran Table (Span 5) -->
        <div class="xl:col-span-5 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-extrabold text-brand-dark">Pengeluaran bulan ini</h3>
                <button onclick="openExpenseModal()" class="px-3 py-1 bg-brand-primary text-white rounded-lg text-[10px] font-extrabold hover:bg-brand-dark transition">+ Tambah</button>
            </div>
            <div class="overflow-x-auto overflow-y-auto max-h-[200px] hide-scrollbar pr-4">
                <table class="w-full text-left text-sm font-bold text-gray-600">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400">
                            <th class="pb-2 font-medium w-16">Tanggal</th>
                            <th class="pb-2 font-medium w-24">Kategori</th>
                            <th class="pb-2 font-medium">Catatan</th>
                            <th class="pb-2 font-medium text-right">Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr class="border-b border-gray-50">
                            <td class="py-3">{{ \Carbon\Carbon::parse($expense->expense_date)->translatedFormat('d M') }}</td>
                            <td class="py-3 text-blue-400">{{ $expense->category }}</td>
                            <td class="py-3 text-gray-400 font-normal truncate max-w-[100px]" title="{{ $expense->notes }}">{{ $expense->notes ?: '-' }}</td>
                            <td class="py-3 text-right text-red-500">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-400">Belum ada pengeluaran dicatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Rekap (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <h3 class="text-base font-extrabold text-brand-dark mb-4">Rekap bulan {{ $dateContext->translatedFormat('F') }}</h3>
            <div class="space-y-4 text-xs font-bold text-gray-500">
                <div class="flex justify-between">
                    <span>Total bulan ini</span>
                    <span class="text-green-600">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Bulan lalu {{ $dateContext->copy()->subMonth()->translatedFormat('F') }}</span>
                    <span>Rp {{ number_format($lastMonthRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="h-px w-full bg-gray-100"></div>
                <div class="flex justify-between">
                    <span>Selisih</span>
                    <span class="{{ $difference >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ $difference >= 0 ? '+' : '' }}Rp {{ number_format($difference, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pertumbuhan</span>
                    <span class="{{ $growth >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ $growth >= 0 ? '+' : '' }}{{ round($growth, 1) }}%</span>
                </div>
                <div class="pt-4">
                    <div x-data="{ editing: false }" class="flex justify-between items-center text-[10px] mb-1">
                        <div class="flex items-center gap-1">
                            <span class="text-brand-primary">Target bulan ini</span>
                            <button @click="editing = !editing" class="text-gray-400 hover:text-brand-primary transition" title="Edit Target">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </div>
                        <div x-show="!editing">
                            <span class="text-gray-500 font-bold">Rp {{ number_format($target, 0, ',', '.') }}</span>
                        </div>
                        <div x-show="editing" style="display: none;">
                            <form action="{{ route('admin.finance.update-target') }}" method="POST" class="flex items-center gap-1">
                                @csrf
                                <input type="number" name="target" value="{{ $target }}" class="w-24 text-right bg-gray-50 border border-gray-200 rounded px-1.5 py-0.5 text-gray-600 text-[10px] outline-none focus:border-brand-primary font-bold">
                                <button type="submit" class="p-0.5 bg-brand-primary text-white rounded hover:bg-brand-dark transition" title="Simpan">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @php $achieved = $target > 0 ? ($monthlyRevenue / $target) * 100 : 100; if($achieved > 100) $achieved = 100; @endphp
                    <div class="h-1.5 w-full bg-gray-100 rounded-full mb-1 mt-2">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $achieved }}%"></div>
                    </div>
                    <div class="text-[9px] text-brand-primary">{{ round($achieved, 1) }}% tercapai</div>
                </div>
            </div>
        </div>

        <!-- Laporan Perhari (Span 3) -->
        <div class="xl:col-span-3 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-brand-dark mb-1">Laporan hari ini</h3>
                <p class="text-[10px] font-bold text-gray-400 mb-4">{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
                
                <p class="text-[10px] font-bold text-gray-500 mb-1">Total Pemasukan</p>
                <h2 class="text-2xl font-black text-brand-dark mb-6">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
                
                <h4 class="text-[10px] font-bold text-brand-primary mb-3 uppercase tracking-wide">Metode Pembayaran</h4>
                <div class="space-y-3">
                    @php
                        $colors = ['bg-blue-400', 'bg-green-500', 'bg-orange-400', 'bg-purple-500'];
                    @endphp
                    @forelse($todayMethods as $idx => $method)
                    <div class="flex items-center justify-between text-[10px] font-bold">
                        <span class="w-12 text-gray-500">{{ strtoupper($method->name) }}</span>
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full mx-3"><div class="h-full {{ $colors[$idx % count($colors)] }} rounded-full shadow-sm" style="width: {{ $todayRevenue > 0 ? ($method->total / $todayRevenue) * 100 : 0 }}%"></div></div>
                        <span class="w-16 text-right text-brand-dark">{{ number_format($method->total, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="text-[10px] font-bold text-gray-400">Belum ada pemasukan hari ini.</div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('admin.finance.print', ['type' => 'daily', 'date' => \Carbon\Carbon::today()->toDateString()]) }}" target="_blank" class="block text-center mt-6 w-full py-2.5 bg-brand-bg border border-gray-100 text-brand-dark text-[10px] font-extrabold rounded-xl hover:bg-gray-100 hover:text-brand-primary transition shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                Unduh Rekap Harian
            </a>
        </div>

        <!-- Transaksi (Span 5) -->
        <div class="xl:col-span-5 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <h3 class="text-base font-extrabold text-brand-dark mb-4">Transaksi Bulan {{ $dateContext->translatedFormat('F') }}</h3>
            <div class="overflow-x-auto overflow-y-auto max-h-[350px] hide-scrollbar pr-4">
                <table class="w-full text-left text-xs font-bold text-gray-600">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 text-[10px]">
                            <th class="pb-2 font-medium">Tanggal</th>
                            <th class="pb-2 font-medium">Klien</th>
                            <th class="pb-2 font-medium">Jenis Paket</th>
                            <th class="pb-2 font-medium">Nominal</th>
                            <th class="pb-2 font-medium text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="py-3">{{ \Carbon\Carbon::parse($payment->verified_at)->translatedFormat('d M Y') }}</td>
                            <td class="py-3">{{ $payment->booking?->user?->name ?? 'Guest' }}</td>
                            <td class="py-3 text-blue-400 font-normal">{{ $payment->booking?->package?->name ?? 'Paket Kustom' }}</td>
                            <td class="py-3 text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="py-3 text-center"><span class="bg-green-500 text-white px-3 py-1 rounded-full text-[9px]">Lunas</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-xs">Belum ada transaksi diverifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>

    </div>
</div>


<!-- ================= TAHUNAN VIEW ================= -->
<div id="view-tahunan" class="space-y-6 hidden">
    <!-- 5 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Total Pemasukan</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold {{ $yearlyGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ $yearlyGrowth >= 0 ? '+' : '' }}{{ round($yearlyGrowth, 1) }}% vs {{ $year - 1 }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Bulan aktif</p>
                <h3 class="text-base font-black text-brand-dark">{{ $activeMonths }} Bulan</h3>
                <p class="text-[9px] font-bold text-gray-400">Dari 12 bulan</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Rata-rata/bulan</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format($avgPerMonth, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold text-gray-400">Per bulan aktif</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-white shrink-0"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-brand-primary">Bulan terbaik</p>
                <h3 class="text-base font-black text-brand-dark">{{ $bestMonth }}</h3>
                <p class="text-[9px] font-bold text-gray-400">Rp {{ number_format($bestMonthAmount, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Uang keluar Tahun ini</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format($yearlyExpenses, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Grid Layout Tahunan -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        <!-- Bar Chart (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50 flex flex-col">
            <h3 class="text-lg font-extrabold text-brand-dark mb-4">Pemasukan perbulan - {{ $year }}</h3>
            <h2 class="text-2xl font-black text-brand-dark">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</h2>
            <p class="text-[10px] font-bold {{ $yearlyGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} mb-6">{{ $yearlyGrowth >= 0 ? '+' : '' }}{{ round($yearlyGrowth, 1) }}% vs Tahun lalu</p>
            
            <!-- Simulated Bar Chart -->
            <div class="flex-1 flex items-end justify-between gap-1 mt-auto h-40">
                @php $maxMRev = max(1, max($monthlyRevenues)); @endphp
                @for($m = 1; $m <= 12; $m++)
                    @php $h = ($monthlyRevenues[$m] / $maxMRev) * 100; @endphp
                    <div class="w-2.5 {{ $monthlyRevenues[$m] > 0 ? 'bg-brand-primary' : 'bg-gray-200' }} rounded-t-sm" style="height: {{ max(1, $h) }}%" title="{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}: Rp {{ number_format($monthlyRevenues[$m], 0, ',', '.') }}"></div>
                @endfor
            </div>
            <!-- X Axis -->
            <div class="flex justify-between text-[8px] font-bold text-gray-400 mt-2">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span><span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
            </div>
        </div>

        <!-- Semua bulan (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <h3 class="text-sm font-extrabold text-brand-dark mb-4">Semua bulan</h3>
            <div class="grid grid-cols-3 gap-3">
                @for($m = 1; $m <= 12; $m++)
                <div class="bg-gray-50 rounded-lg p-2">
                    <p class="text-[10px] font-bold text-gray-500">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</p>
                    <p class="text-[8px] text-gray-400 mb-1">{{ $monthlyRevenues[$m] > 0 ? 'Rp ' . number_format($monthlyRevenues[$m], 0, ',', '.') : '-' }}</p>
                    <div class="w-full h-1 bg-gray-200 rounded-full">
                        @if($monthlyRevenues[$m] > 0)
                        <div class="h-full bg-brand-primary rounded-full" style="width: {{ ($monthlyRevenues[$m] / $maxMRev) * 100 }}%"></div>
                        @endif
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Pengeluaran Table (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-extrabold text-brand-dark">Pengeluaran Tahun ini</h3>
                <button onclick="openExpenseModal()" class="px-3 py-1 bg-brand-primary text-white rounded-lg text-[10px] font-extrabold hover:bg-brand-dark transition">+ Tambah</button>
            </div>
            <div class="overflow-x-auto overflow-y-auto max-h-[300px] hide-scrollbar pr-4">
                <table class="w-full text-left text-[10px] font-bold text-gray-600">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400">
                            <th class="pb-2 font-medium w-12">Tanggal</th>
                            <th class="pb-2 font-medium w-16">Kategori</th>
                            <th class="pb-2 font-medium">Catatan</th>
                            <th class="pb-2 font-medium text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($yearlyExpenseList as $expense)
                        <tr class="border-b border-gray-50">
                            <td class="py-3">{{ \Carbon\Carbon::parse($expense->expense_date)->translatedFormat('d M') }}</td>
                            <td class="py-3 text-blue-400">{{ $expense->category }}</td>
                            <td class="py-3 text-gray-400 font-normal truncate max-w-[100px]" title="{{ $expense->notes }}">{{ $expense->notes ?: '-' }}</td>
                            <td class="py-3 text-right text-red-500">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center">Belum ada pengeluaran tahun ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Rekap (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <h3 class="text-base font-extrabold text-brand-dark mb-4">Rekap {{ $year }}</h3>
            <div class="space-y-4 text-xs font-bold text-gray-500">
                <div class="flex justify-between">
                    <span>Total tahun ini</span>
                    <span class="text-green-600">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tahun lalu {{ $year - 1 }}</span>
                    <span>Rp {{ number_format($lastYearRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="h-px w-full bg-gray-100"></div>
                <div class="flex justify-between">
                    <span>Selisih</span>
                    <span class="{{ $yearlyRevenue - $lastYearRevenue >= 0 ? 'text-green-500' : 'text-red-500' }}">Rp {{ number_format($yearlyRevenue - $lastYearRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pertumbuhan</span>
                    <span class="{{ $yearlyGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ $yearlyGrowth >= 0 ? '+' : '' }}{{ round($yearlyGrowth, 1) }}%</span>
                </div>
                <div class="pt-4">
                    <div x-data="{ editing: false }" class="flex justify-between items-center text-[10px] mb-1">
                        <div class="flex items-center gap-1">
                            <span class="text-brand-primary">Target tahun ini</span>
                            <button @click="editing = !editing" class="text-gray-400 hover:text-brand-primary transition" title="Edit Target">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </div>
                        <div x-show="!editing">
                            @php
                                $yearlyTargetSetting = \App\Models\Setting::where('key', 'yearly_target')->first();
                                $yearlyTarget = $yearlyTargetSetting ? (float) $yearlyTargetSetting->value : 200000000;
                            @endphp
                            <span class="text-gray-500 font-bold">Rp {{ number_format($yearlyTarget, 0, ',', '.') }}</span>
                        </div>
                        <div x-show="editing" style="display: none;">
                            <form action="{{ route('admin.finance.update-target') }}" method="POST" class="flex items-center gap-1">
                                @csrf
                                <input type="hidden" name="type" value="yearly">
                                <input type="number" name="target" value="{{ $yearlyTarget }}" class="w-24 text-right bg-gray-50 border border-gray-200 rounded px-1.5 py-0.5 text-gray-600 text-[10px] outline-none focus:border-brand-primary font-bold">
                                <button type="submit" class="p-0.5 bg-brand-primary text-white rounded hover:bg-brand-dark transition" title="Simpan">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @php $achievedY = $yearlyTarget > 0 ? ($yearlyRevenue / $yearlyTarget) * 100 : 100; if($achievedY > 100) $achievedY = 100; @endphp
                    <div class="h-1.5 w-full bg-gray-100 rounded-full mb-1 mt-2">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $achievedY }}%"></div>
                    </div>
                    <div class="text-[9px] text-brand-primary">{{ round($achievedY, 1) }}% tercapai</div>
                </div>
            </div>
        </div>

        <!-- Transaksi (Span 8) -->
        <div class="xl:col-span-8 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-extrabold text-brand-dark">Transaksi terbaru</h3>
                <span class="text-[10px] font-bold text-gray-400">Terakhir • {{ $year }}</span>
            </div>
            <div class="overflow-x-auto max-h-[300px] hide-scrollbar">
                <table class="w-full text-left text-xs font-bold text-gray-600">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 text-[10px]">
                            <th class="pb-2 font-medium">Tanggal</th>
                            <th class="pb-2 font-medium">Klien</th>
                            <th class="pb-2 font-medium">Jenis Paket</th>
                            <th class="pb-2 font-medium">Nominal</th>
                            <th class="pb-2 font-medium text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments->take(10) as $payment)
                        <tr class="border-b border-gray-50">
                            <td class="py-3">{{ \Carbon\Carbon::parse($payment->verified_at)->translatedFormat('d M') }}</td>
                            <td class="py-3">{{ $payment->booking->user->name ?? 'Guest' }}</td>
                            <td class="py-3 text-blue-400 font-normal">{{ $payment->booking->package->name ?? '-' }}</td>
                            <td class="py-3 text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="py-3 text-center"><span class="bg-green-500 text-white px-3 py-1 rounded-full text-[9px]">Lunas</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-4 text-center">Belum ada transaksi di tahun ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Expense Modal -->
<div id="expense-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-sm transition-opacity opacity-0" id="expense-modal-backdrop" onclick="closeExpenseModal()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-[24px] shadow-xl w-full max-w-md relative z-10 transform scale-95 opacity-0 transition-all duration-300" id="expense-modal-content">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-extrabold text-brand-dark">Tambah Pengeluaran</h3>
            <button onclick="closeExpenseModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-brand-dark hover:bg-gray-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.finance.store-expense') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Tanggal Pengeluaran</label>
                    <input type="text" id="expense_date" name="expense_date" required value="{{ date('Y-m-d') }}" placeholder="Pilih tanggal..." class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Kategori</label>
                    <input type="text" name="category" required placeholder="Contoh: Beli Kopi, Listrik, Bensin..." class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Nominal (Rp)</label>
                    <input type="number" name="amount" required min="1" placeholder="Contoh: 50000" class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Detail pengeluaran..." class="w-full bg-white border border-gray-200 text-brand-dark text-xs font-bold rounded-xl px-4 py-3 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm resize-none"></textarea>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/50 rounded-b-[24px]">
                <button type="button" onclick="closeExpenseModal()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-extrabold rounded-xl transition">Batal</button>
                <button type="submit" class="px-8 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-xs font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#expense_date", {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "id"
        });

        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'bulanan';
        switchTab(activeTab);
    });

    function switchTab(tab) {
        const btnBulanan = document.getElementById('tab-bulanan-btn');
        const btnTahunan = document.getElementById('tab-tahunan-btn');
        const viewBulanan = document.getElementById('view-bulanan');
        const viewTahunan = document.getElementById('view-tahunan');
        const periodBulanan = document.getElementById('period-bulanan');
        const periodTahunan = document.getElementById('period-tahunan');
        const btnCetakHeader = document.getElementById('btn-cetak-header');

        if(tab === 'bulanan') {
            btnBulanan.className = 'px-5 py-2 rounded-md text-sm font-bold bg-gray-200 text-brand-dark transition';
            btnTahunan.className = 'px-5 py-2 rounded-md text-sm font-bold bg-transparent text-gray-500 hover:text-brand-dark transition';
            viewBulanan.classList.remove('hidden');
            viewTahunan.classList.add('hidden');
            periodBulanan.classList.remove('hidden');
            periodBulanan.classList.add('flex');
            periodTahunan.classList.add('hidden');
            periodTahunan.classList.remove('flex');
            if (btnCetakHeader) btnCetakHeader.href = "{{ route('admin.finance.print', ['type' => 'monthly', 'month' => $dateContext->month, 'year' => $year]) }}";
        } else {
            btnTahunan.className = 'px-5 py-2 rounded-md text-sm font-bold bg-gray-200 text-brand-dark transition';
            btnBulanan.className = 'px-5 py-2 rounded-md text-sm font-bold bg-transparent text-gray-500 hover:text-brand-dark transition';
            viewTahunan.classList.remove('hidden');
            viewBulanan.classList.add('hidden');
            periodTahunan.classList.remove('hidden');
            periodTahunan.classList.add('flex');
            periodBulanan.classList.add('hidden');
            periodBulanan.classList.remove('flex');
            if (btnCetakHeader) btnCetakHeader.href = "{{ route('admin.finance.print', ['type' => 'yearly', 'year' => $year]) }}";
        }
    }

    function openExpenseModal() {
        const modal = document.getElementById('expense-modal');
        const backdrop = document.getElementById('expense-modal-backdrop');
        const content = document.getElementById('expense-modal-content');
        
        modal.classList.remove('hidden');
        void modal.offsetWidth; // trigger reflow
        backdrop.classList.remove('opacity-0');
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    }

    function closeExpenseModal() {
        const modal = document.getElementById('expense-modal');
        const backdrop = document.getElementById('expense-modal-backdrop');
        const content = document.getElementById('expense-modal-content');
        
        backdrop.classList.add('opacity-0');
        content.classList.remove('opacity-100', 'scale-100');
        content.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

@endsection
