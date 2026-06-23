<?php

$file = __DIR__ . '/../resources/views/admin/users/index.blade.php';
$content = file_get_contents($file);

// 1. Make the "Lihat ulasan" button dynamic and clickable
$oldReviewBox = <<<EOT
                    <div class="bg-yellow-50 rounded-xl p-3 border border-yellow-100 text-center cursor-pointer hover:bg-yellow-100 transition">
                        <h5 class="text-sm font-black text-yellow-600 mb-0.5 mt-0.5 flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            4.9
                        </h5>
                        <p class="text-[9px] font-bold text-yellow-700">Lihat ulasan</p>
                    </div>
EOT;

$newReviewBox = <<<EOT
                    @php
                        \$avgRating = \$employee->reviewsReceived->avg('rating') ?? 0;
                        \$reviewCount = \$employee->reviewsReceived->count();
                    @endphp
                    <div onclick="openReviewModal({{ \$employee->id }})" class="bg-yellow-50 rounded-xl p-3 border border-yellow-100 text-center cursor-pointer hover:bg-yellow-100 transition">
                        <h5 class="text-sm font-black text-yellow-600 mb-0.5 mt-0.5 flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            {{ number_format(\$avgRating, 1) }}
                        </h5>
                        <p class="text-[9px] font-bold text-yellow-700">Lihat ulasan</p>
                    </div>
EOT;

$content = str_replace($oldReviewBox, $newReviewBox, $content);

// 2. We need to extract the review-modal from outside the loop and put it inside.
// Find the exact review-modal block
$startStr = '<!-- Modal Ulasan Klien -->';
$endStr = '<!-- Modal Detail Pelanggan Loops -->';

$startIndex = strpos($content, $startStr);
$endIndex = strpos($content, $endStr);

if ($startIndex !== false && $endIndex !== false) {
    $reviewModalBlock = substr($content, $startIndex, $endIndex - $startIndex);
    
    // Remove it from the original location
    $content = str_replace($reviewModalBlock, '', $content);
    
    // Now make the modal dynamic
    $dynamicReviewModal = str_replace('id="review-modal"', 'id="review-modal-{{ $employee->id }}"', $reviewModalBlock);
    $dynamicReviewModal = str_replace('id="review-modal-content"', 'id="review-modal-content-{{ $employee->id }}"', $dynamicReviewModal);
    $dynamicReviewModal = str_replace('onclick="closeReviewModal()"', 'onclick="closeReviewModal({{ $employee->id }})"', $dynamicReviewModal);
    $dynamicReviewModal = str_replace('Menampilkan ulasan dari Sari Wulandari', 'Menampilkan ulasan dari {{ $employee->name }}', $dynamicReviewModal);
    
    // Replace the big hardcoded 4.9 in the stats
    $dynamicReviewModal = preg_replace('/<h2 class="text-5xl font-black text-brand-dark leading-none mb-2">4\.9<\/h2>/', '<h2 class="text-5xl font-black text-brand-dark leading-none mb-2">{{ number_format($avgRating, 1) }}</h2>', $dynamicReviewModal);
    $dynamicReviewModal = preg_replace('/<p class="text-\[10px\] font-bold text-gray-400">78 Ulasan<\/p>/', '<p class="text-[10px] font-bold text-gray-400">{{ $reviewCount }} Ulasan</p>', $dynamicReviewModal);
    
    // Replace the review list with a real loop
    $reviewListStart = '<!-- Review List -->';
    $reviewListEnd = '<!-- Modal Detail Pelanggan Loops -->'; // This won't work perfectly, let's target the exact div structure
    
    $dynamicReviewModal = preg_replace('/<!-- Review List -->.*?<\/div>.*?<\/div>.*?<\/div>.*?<\/div>/s', <<<EOT
            <!-- Review List -->
            <div class="border-t border-gray-100 pt-6 space-y-6">
                @forelse(\$employee->reviewsReceived as \$review)
                <div class="{{ \$loop->first ? '' : 'border-t border-gray-50 pt-6' }}">
                    <div class="flex justify-between items-start mb-1.5">
                        <h4 class="text-xs font-extrabold text-brand-dark">{{ \$review->customer->name ?? 'Anonim' }}</h4>
                        <span class="text-[9px] font-bold text-gray-400">{{ \$review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex text-yellow-400 mb-2">
                        @for(\$i = 1; \$i <= 5; \$i++)
                            @if(\$i <= \$review->rating)
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @else
                                <svg class="w-3 h-3 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endif
                        @endfor
                    </div>
                    <p class="text-[11px] font-bold text-gray-500 leading-relaxed">{{ \$review->comment }}</p>
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
EOT, $dynamicReviewModal);
    
    // Inject it just before the `@endforeach`
    $content = str_replace('@endforeach', "\n" . $dynamicReviewModal . "\n@endforeach", $content);
}

// 3. Add javascript functions
$oldJs = "    function openEmployeeDetailModal(id) {";
$newJs = <<<EOT
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
EOT;

$content = str_replace($oldJs, $newJs, $content);

// Remove the old closeReviewModal() 
$content = preg_replace('/function closeReviewModal\(\) \{.*?\}/s', '', $content);


file_put_contents($file, $content);

echo "Refactored UI successfully.";
