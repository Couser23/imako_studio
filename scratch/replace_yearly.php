<?php
$file = 'c:\\laragon\\www\\imakostudio\\resources\\views\\admin\\finance\\index.blade.php';
$content = file_get_contents($file);

$startMarker = '<!-- ================= TAHUNAN VIEW ================= -->';
$endMarker = '<script>';

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker, $startPos);

if ($startPos !== false && $endPos !== false) {
    $newContent = <<<HTML
<!-- ================= TAHUNAN VIEW ================= -->
<div id="view-tahunan" class="space-y-6 hidden">
    <!-- 5 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Total Pemasukan</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format(\$yearlyRevenue, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold {{ \$yearlyGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ \$yearlyGrowth >= 0 ? '+' : '' }}{{ round(\$yearlyGrowth, 1) }}% vs {{ \$year - 1 }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Bulan aktif</p>
                <h3 class="text-base font-black text-brand-dark">{{ \$activeMonths }} Bulan</h3>
                <p class="text-[9px] font-bold text-gray-400">Dari 12 bulan</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Rata-rata/bulan</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format(\$avgPerMonth, 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold text-gray-400">Per bulan aktif</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-white shrink-0"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-brand-primary">Bulan terbaik</p>
                <h3 class="text-base font-black text-brand-dark">{{ \$bestMonth }}</h3>
                <p class="text-[9px] font-bold text-gray-400">Rp {{ number_format(\$bestMonthAmount, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-3 border border-gray-50">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-xs font-bold text-blue-400">Uang keluar Tahun ini</p>
                <h3 class="text-base font-black text-brand-dark">Rp {{ number_format(\$yearlyExpenses, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Grid Layout Tahunan -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        <!-- Bar Chart (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50 flex flex-col">
            <h3 class="text-lg font-extrabold text-brand-dark mb-4">Pemasukan perbulan - {{ \$year }}</h3>
            <h2 class="text-2xl font-black text-brand-dark">Rp {{ number_format(\$yearlyRevenue, 0, ',', '.') }}</h2>
            <p class="text-[10px] font-bold {{ \$yearlyGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} mb-6">{{ \$yearlyGrowth >= 0 ? '+' : '' }}{{ round(\$yearlyGrowth, 1) }}% vs Tahun lalu</p>
            
            <!-- Simulated Bar Chart -->
            <div class="flex-1 flex items-end justify-between gap-1 mt-auto h-40">
                @php \$maxMRev = max(1, max(\$monthlyRevenues)); @endphp
                @for(\$m = 1; \$m <= 12; \$m++)
                    @php \$h = (\$monthlyRevenues[\$m] / \$maxMRev) * 100; @endphp
                    <div class="w-2.5 {{ \$monthlyRevenues[\$m] > 0 ? 'bg-brand-primary' : 'bg-gray-200' }} rounded-t-sm" style="height: {{ max(1, \$h) }}%" title="{{ \Carbon\Carbon::create()->month(\$m)->translatedFormat('F') }}: Rp {{ number_format(\$monthlyRevenues[\$m], 0, ',', '.') }}"></div>
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
                @for(\$m = 1; \$m <= 12; \$m++)
                <div class="bg-gray-50 rounded-lg p-2">
                    <p class="text-[10px] font-bold text-gray-500">{{ \Carbon\Carbon::create()->month(\$m)->translatedFormat('F') }}</p>
                    <p class="text-[8px] text-gray-400 mb-1">{{ \$monthlyRevenues[\$m] > 0 ? 'Rp ' . number_format(\$monthlyRevenues[\$m], 0, ',', '.') : '-' }}</p>
                    <div class="w-full h-1 bg-gray-200 rounded-full">
                        @if(\$monthlyRevenues[\$m] > 0)
                        <div class="h-full bg-brand-primary rounded-full" style="width: {{ (\$monthlyRevenues[\$m] / \$maxMRev) * 100 }}%"></div>
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
            </div>
            <div class="overflow-x-auto max-h-[300px] hide-scrollbar">
                <table class="w-full text-left text-[11px] font-bold text-gray-600">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400">
                            <th class="pb-2 font-medium">Tanggal</th>
                            <th class="pb-2 font-medium">Kategori</th>
                            <th class="pb-2 font-medium text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\$yearlyExpenseList as \$expense)
                        <tr class="border-b border-gray-50">
                            <td class="py-3">{{ \Carbon\Carbon::parse(\$expense->expense_date)->translatedFormat('d M') }}</td>
                            <td class="py-3 text-blue-400">{{ \$expense->category }}</td>
                            <td class="py-3 text-right">Rp {{ number_format(\$expense->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center">Belum ada pengeluaran tahun ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Rekap (Span 4) -->
        <div class="xl:col-span-4 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <h3 class="text-base font-extrabold text-brand-dark mb-4">Rekap {{ \$year }}</h3>
            <div class="space-y-4 text-xs font-bold text-gray-500">
                <div class="flex justify-between">
                    <span>Total tahun ini</span>
                    <span class="text-green-600">Rp {{ number_format(\$yearlyRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tahun lalu {{ \$year - 1 }}</span>
                    <span>Rp {{ number_format(\$lastYearRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="h-px w-full bg-gray-100"></div>
                <div class="flex justify-between">
                    <span>Selisih</span>
                    <span class="{{ \$yearlyRevenue - \$lastYearRevenue >= 0 ? 'text-green-500' : 'text-red-500' }}">Rp {{ number_format(\$yearlyRevenue - \$lastYearRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pertumbuhan</span>
                    <span class="{{ \$yearlyGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ \$yearlyGrowth >= 0 ? '+' : '' }}{{ round(\$yearlyGrowth, 1) }}%</span>
                </div>
                <div class="pt-4">
                    <div class="flex justify-between items-center text-[10px] mb-1">
                        <span class="text-brand-primary">Target tahun ini</span>
                        <form action="{{ route('admin.finance.update-target') }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            <input type="hidden" name="type" value="yearly">
                            @php
                                \$yearlyTargetSetting = \App\Models\Setting::where('key', 'yearly_target')->first();
                                \$yearlyTarget = \$yearlyTargetSetting ? (float) \$yearlyTargetSetting->value : 200000000;
                            @endphp
                            <input type="number" name="target" value="{{ \$yearlyTarget }}" class="w-24 text-right bg-gray-50 border border-gray-200 rounded px-2 py-1 text-gray-600 text-[10px] outline-none focus:border-brand-primary font-bold">
                            <button type="submit" class="p-1 bg-brand-primary text-white rounded hover:bg-brand-dark transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </form>
                    </div>
                    @php \$achievedY = \$yearlyTarget > 0 ? (\$yearlyRevenue / \$yearlyTarget) * 100 : 100; if(\$achievedY > 100) \$achievedY = 100; @endphp
                    <div class="h-1.5 w-full bg-gray-100 rounded-full mb-1 mt-2">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ \$achievedY }}%"></div>
                    </div>
                    <div class="text-[9px] text-brand-primary">{{ round(\$achievedY, 1) }}% tercapai</div>
                </div>
            </div>
        </div>

        <!-- Transaksi (Span 8) -->
        <div class="xl:col-span-8 bg-white rounded-[24px] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-extrabold text-brand-dark">Transaksi terbaru</h3>
                <span class="text-[10px] font-bold text-gray-400">Terakhir • {{ \$year }}</span>
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
                        @forelse(\$payments->take(10) as \$payment)
                        <tr class="border-b border-gray-50">
                            <td class="py-3">{{ \Carbon\Carbon::parse(\$payment->verified_at)->translatedFormat('d M') }}</td>
                            <td class="py-3">{{ \$payment->booking->user->name ?? 'Guest' }}</td>
                            <td class="py-3 text-blue-400 font-normal">{{ \$payment->booking->package->name ?? '-' }}</td>
                            <td class="py-3 text-green-600">Rp {{ number_format(\$payment->amount, 0, ',', '.') }}</td>
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

<script>
HTML;

    $content = substr($content, 0, $startPos) . $newContent . substr($content, $endPos + strlen($endMarker));
    file_put_contents($file, $content);
    echo "Successfully replaced yearly view!";
} else {
    echo "Could not find markers.";
}
