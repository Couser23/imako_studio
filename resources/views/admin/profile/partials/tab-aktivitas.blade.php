        <!-- Tab 5: Aktivitas -->
        <div id="tab-aktivitas" class="hidden">
            <!-- Header & Filter -->
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xs font-extrabold text-brand-dark">Log Aktivitas Terbaru</h4>
                <div class="relative">
                    <select id="activity-filter" onchange="filterActivities(this.value)" class="appearance-none bg-white border border-gray-200 text-gray-600 text-[10px] font-extrabold rounded-lg pl-4 pr-10 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                        <option value="all">Semua aktivitas</option>
                        <option value="security">Login & Keamanan</option>
                        <option value="booking">Booking & Pembayaran</option>
                        <option value="settings">Pengaturan Studio</option>
                    </select>
                </div>
            </div>

            <!-- Timeline -->
            <div class="relative space-y-0 pl-2" id="activity-list">
                @if($activityLogs->count() > 0)
                <div class="absolute top-4 bottom-4 left-[26px] w-px bg-gray-100 z-0"></div>

                @foreach($activityLogs as $log)
                @php
                    $category = 'all';
                    $logTitle = strtolower($log->title);
                    if ($log->type === 'login' || str_contains($logTitle, 'password') || str_contains($logTitle, '2fa')) {
                        $category = 'security';
                    } elseif (in_array($log->type, ['assign_employee', 'confirm_payment', 'reject_payment']) || str_contains($logTitle, 'booking') || str_contains($logTitle, 'pembayaran')) {
                        $category = 'booking';
                    } elseif ($log->type === 'update_setting' || str_contains($logTitle, 'studio') || str_contains($logTitle, 'profil') || str_contains($logTitle, 'notifikasi')) {
                        $category = 'settings';
                    }
                @endphp
                <div class="activity-item relative flex items-start gap-5 z-10 group" data-category="{{ $category }}">
                    <div class="w-11 h-11 rounded-full bg-{{ $log->icon_color }}-50 flex items-center justify-center shrink-0 border-4 border-white shadow-sm ring-1 ring-gray-50 group-hover:scale-110 transition">
                        <svg class="w-4 h-4 text-{{ $log->icon_color }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $log->icon_svg !!}</svg>
                    </div>
                    <div class="flex-1 flex justify-between items-start pt-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }} pb-6 group-hover:border-gray-200 transition">
                        <div>
                            <h5 class="text-[11px] font-extrabold text-brand-dark mb-1">{{ $log->title }}</h5>
                            <p class="text-[9px] font-bold text-gray-400">{{ $log->description ?? '' }}</p>
                        </div>
                        <span class="text-[9px] font-bold text-gray-400 shrink-0 mt-0.5">{{ $log->created_at->format('d M Y, H.i') }}</span>
                    </div>
                </div>
                @endforeach

                @else
                <div class="text-center py-16">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h5 class="text-sm font-extrabold text-gray-400 mb-1">Belum ada aktivitas</h5>
                    <p class="text-[10px] font-bold text-gray-300">Log aktivitas akan muncul setelah Anda melakukan aksi.</p>
                </div>
                @endif
            </div>
        </div>

        <script>
            function filterActivities(category) {
                const items = document.querySelectorAll('.activity-item');
                let visibleCount = 0;
                items.forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                let emptyState = document.getElementById('activity-empty-state');
                if (visibleCount === 0 && items.length > 0) {
                    if (!emptyState) {
                        const emptyHtml = `
                        <div id="activity-empty-state" class="text-center py-16 z-10 relative">
                            <h5 class="text-sm font-extrabold text-gray-400 mb-1">Tidak ada aktivitas</h5>
                            <p class="text-[10px] font-bold text-gray-300">Tidak ditemukan aktivitas untuk kategori ini.</p>
                        </div>`;
                        document.getElementById('activity-list').insertAdjacentHTML('beforeend', emptyHtml);
                    } else {
                        emptyState.style.display = 'block';
                    }
                } else if (emptyState) {
                    emptyState.style.display = 'none';
                }
            }
        </script>
