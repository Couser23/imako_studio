<?php
$file = 'c:\\laragon\\www\\imakostudio\\resources\\views\\admin\\employee-schedules\\index.blade.php';
$content = file_get_contents($file);

$startMarker1 = '<!-- Bar Chart Left: Distribusi Sesi per Pegawai -->';
$endMarker1 = '<!-- Vertical Bar Chart Right: Sesi per Hari -->';

$startPos1 = strpos($content, $startMarker1);
$endPos1 = strpos($content, $endMarker1, $startPos1);

if ($startPos1 !== false && $endPos1 !== false) {
    $newContent1 = <<<HTML
<!-- Bar Chart Left: Distribusi Sesi per Pegawai -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-extrabold text-brand-dark">Distribusi Sesi per Pegawai</h3>
            <p class="text-[10px] font-bold text-gray-400 mb-6">{{ \$currentDate->translatedFormat('F Y') }}</p>
            
            <div class="flex flex-col gap-4">
                @php
                    \$colors = ['purple', 'pink', 'teal', 'orange', 'emerald', 'blue', 'indigo'];
                    \$monthStart = \$currentDate->copy()->startOfMonth();
                    \$monthEnd = \$currentDate->copy()->endOfMonth();
                    \$maxSessions = 1;
                    \$employeeStats = [];
                    foreach(\$employees as \$emp) {
                        \$count = \$emp->assignments()->whereHas('booking', function(\$q) use (\$monthStart, \$monthEnd) {
                            \$q->whereBetween('booking_date', [\$monthStart, \$monthEnd]);
                        })->count();
                        if (\$count > \$maxSessions) \$maxSessions = \$count;
                        \$employeeStats[] = ['emp' => \$emp, 'count' => \$count];
                    }
                    usort(\$employeeStats, function(\$a, \$b) { return \$b['count'] <=> \$a['count']; });
                @endphp
                
                @forelse(array_slice(\$employeeStats, 0, 5) as \$index => \$stat)
                    @php
                        \$percentage = max(5, (\$stat['count'] / \$maxSessions) * 100);
                        \$color = \$colors[\$index % count(\$colors)];
                    @endphp
                    <div class="flex items-center gap-4">
                        <span class="text-[11px] font-bold text-gray-500 w-8 shrink-0 truncate">{{ \$stat['emp']->name }}</span>
                        <div class="w-full h-5 bg-gray-100 rounded-md overflow-hidden relative">
                            <div class="h-full bg-{{ \$color }}-500 rounded-md flex items-center px-2 transition-all duration-1000" style="width: {{ \$percentage }}%">
                                <span class="text-[9px] font-black text-white">{{ \$stat['count'] }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-[11px] font-bold text-gray-400 text-center py-4">Belum ada sesi di bulan ini</p>
                @endforelse
            </div>
        </div>

        
HTML;

    $content = substr($content, 0, $startPos1) . $newContent1 . substr($content, $endPos1);
}

// Now replace Vertical Bar Chart Right
$startMarker2 = '<!-- Vertical Bar Chart Right: Sesi per Hari -->';
$endMarker2 = '<!-- Status Real-time Pegawai -->';

$startPos2 = strpos($content, $startMarker2);
$endPos2 = strpos($content, $endMarker2, $startPos2);

if ($startPos2 !== false && $endPos2 !== false) {
    // The previous div ends before <!-- Status Real-time Pegawai -->
    // So we just replace up to the closing tags
    $endMarkerClose = "</div>\n</div>\n\n<!-- Status Real-time Pegawai -->";
    $endPos2 = strpos($content, $endMarkerClose, $startPos2);
    
    if ($endPos2 !== false) {
        $newContent2 = <<<HTML
<!-- Vertical Bar Chart Right: Sesi per Hari -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-extrabold text-brand-dark">Sesi per Hari (Minggu ini)</h3>
            <p class="text-[10px] font-bold text-gray-400 mb-6">{{ \$currentDate->copy()->startOfWeek()->translatedFormat('d') }} - {{ \$currentDate->copy()->endOfWeek()->translatedFormat('d F Y') }}</p>
            
            <div class="h-40 flex items-end justify-between px-2 gap-2 border-b border-gray-100 pb-2">
                @php
                    \$weekStart = \$currentDate->copy()->startOfWeek();
                    \$dailyCounts = [];
                    \$maxDaily = 1;
                    for(\$i = 0; \$i < 7; \$i++) {
                        \$date = \$weekStart->copy()->addDays(\$i);
                        \$count = \App\Models\Assignment::whereHas('booking', function(\$q) use (\$date) {
                            \$q->whereDate('booking_date', \$date);
                        })->count();
                        \$dailyCounts[] = \$count;
                        if(\$count > \$maxDaily) \$maxDaily = \$count;
                    }
                @endphp
                @foreach(\$dailyCounts as \$count)
                <div class="flex flex-col items-center gap-1 w-full group">
                    <span class="text-[9px] font-bold text-gray-400 opacity-0 group-hover:opacity-100 transition">{{ \$count > 0 ? \$count : '-' }}</span>
                    <div class="w-full bg-brand-dark rounded-t-sm hover:opacity-90 transition cursor-pointer" style="height: {{ \$count > 0 ? max(5, (\$count / \$maxDaily) * 100) : 2 }}%; opacity: {{ \$count > 0 ? 1 : 0.2 }}"></div>
                </div>
                @endforeach
            </div>
            <!-- Labels -->
            <div class="flex justify-between px-2 pt-2 text-[10px] font-bold text-gray-400 text-center">
                <span class="w-full text-brand-dark font-extrabold">Sen</span>
                <span class="w-full">Sel</span>
                <span class="w-full">Rab</span>
                <span class="w-full">Kam</span>
                <span class="w-full">Jum</span>
                <span class="w-full">Sab</span>
                <span class="w-full">Min</span>
            </div>
        </div>

    
HTML;
        $content = substr($content, 0, $startPos2) . $newContent2 . substr($content, $endPos2);
    }
}

file_put_contents($file, $content);
echo "Replaced charts!";
