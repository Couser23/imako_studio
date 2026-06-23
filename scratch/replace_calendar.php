<?php
$file = 'c:\\laragon\\www\\imakostudio\\resources\\views\\admin\\schedules\\index.blade.php';
$content = file_get_contents($file);

$startMarker = '<!-- Calendar Body -->';
$endMarker = '</div> <!-- End of view-kalender -->';

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker, $startPos);

if ($startPos !== false && $endPos !== false) {
    $newContent = <<<HTML
<!-- Calendar Body -->
    <div class="flex-1 grid grid-cols-7 bg-gray-100 gap-px">
        @foreach(\$calendar as \$cell)
        <div class="{{ \$cell['date']->isToday() ? 'bg-blue-50/40 border-[1.5px] border-brand-dark rounded' : (\$cell['is_current_month'] ? 'bg-white' : 'bg-gray-50/50') }} p-2 min-h-[100px] flex flex-col gap-1.5 relative z-10 shadow-sm">
            <span class="text-[11px] {{ \$cell['date']->isToday() ? 'font-extrabold text-white bg-brand-dark w-5 h-5 rounded-full flex items-center justify-center mb-1' : (\$cell['is_current_month'] ? 'font-bold text-brand-dark' : 'font-bold text-gray-300') }}">
                {{ \$cell['day'] }}
            </span>
            
            @foreach(\$cell['events']->take(3) as \$event)
            <!-- Event block -->
            <div class="px-2 py-1 bg-blue-100 text-blue-700 text-[9px] font-bold rounded cursor-pointer hover:bg-blue-200 transition truncate" onclick="openDetailModal({{ \$event->id }})">
                {{ \Carbon\Carbon::parse(\$event->start_time)->format('H.i') }} {{ \$event->user->name ?? 'Pelanggan' }} - {{ \$event->package->name ?? '-' }}
            </div>
            @endforeach
            
            @if(count(\$cell['events']) > 3)
            <p class="text-[9px] text-gray-400 font-bold mt-1">+{{ count(\$cell['events']) - 3 }} lainnya</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
HTML;

    $content = substr($content, 0, $startPos) . $newContent . "\n" . substr($content, $endPos);
    file_put_contents($file, $content);
    echo "Successfully replaced calendar body!";
} else {
    echo "Could not find markers.";
}
