@extends('layouts.user', ['title' => 'Hasil Foto'])

@section('content')
<div class="mb-6 -mt-4">
    <p class="text-sm text-gray-400 font-medium">Akses dan unduh hasil foto sesi Anda</p>
</div>

<div x-data="resultsView()">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        
        <div class="mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-lg font-extrabold text-brand-dark mb-1">Hasil Foto Saya</h2>
            <p class="text-xs text-gray-400 font-medium">Link folder Google Drive dikirim oleh fotografer setelah editing selesai</p>
        </div>

        <div class="flex flex-col gap-4">
            
            @forelse($results as $result)
            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-[#E8F0FE] flex items-center justify-center shrink-0">
                        @if($result->package && $result->package->image)
                            <img src="{{ asset('images/paket/' . $result->package->image) }}" alt="img" class="w-full h-full object-cover rounded-lg">
                        @else
                            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($result->package->name ?? 'P') . '&background=F1F5F9&color=2B5488&size=512' }}" alt="img" class="w-full h-full object-cover rounded-lg">
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-sm font-extrabold text-gray-800">{{ $result->package->name ?? 'Paket' }}</h3>
                            @if(now()->diffInDays($result->updated_at) <= 3)
                                <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-500 text-[9px] font-extrabold border border-emerald-100">Baru!</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-500 font-medium mb-0.5">{{ \Carbon\Carbon::parse($result->booking_date)->translatedFormat('d F Y') }} • #IMK-{{ $result->booking_code }}</p>
                        <p class="text-[10px] text-gray-400">Dikirim: {{ \Carbon\Carbon::parse($result->updated_at)->translatedFormat('d M, H:i') }}</p>
                    </div>
                </div>
                
                @php
                    $assignmentsData = [];
                    foreach ($result->assignments as $assignment) {
                        $assignmentsData[] = [
                            'employee_id' => $assignment->employee_id,
                            'name' => $assignment->employee->name ?? 'Fotografer',
                            'avatar' => $assignment->employee->avatar ? asset('images/profile_akun/pegawai/' . $assignment->employee->avatar) : null,
                            'has_reviewed' => \App\Models\Review::where('booking_id', $result->id)
                                                ->where('employee_id', $assignment->employee_id)
                                                ->where('customer_id', auth()->id())
                                                ->exists()
                        ];
                    }
                    $imagePath = ($result->package && $result->package->image) ? asset('images/paket/' . $result->package->image) : 'https://ui-avatars.com/api/?name=' . urlencode($result->package->name ?? 'P') . '&background=F1F5F9&color=2B5488&size=512';
                    $resultLink = $result->result_link ?? '#';
                    $resultNotes = $result->result_notes ?? '';
                    $hasReviewedStudio = \App\Models\Review::where('booking_id', $result->id)
                                            ->whereNull('employee_id')
                                            ->where('customer_id', auth()->id())
                                            ->exists();
                @endphp
                <button 
                    data-name="{{ $result->package->name ?? 'Paket' }}"
                    data-image="{{ $imagePath }}"
                    data-assignments="{{ json_encode($assignmentsData) }}"
                    data-link="{{ $resultLink }}"
                    data-notes="{{ $resultNotes }}"
                    data-code="{{ $result->booking_code }}"
                    data-studio-reviewed="{{ $hasReviewedStudio ? 'true' : 'false' }}"
                    @click.prevent="openResult({{ $result->id }}, $el.dataset.name, $el.dataset.image, JSON.parse($el.dataset.assignments), $el.dataset.link, $el.dataset.code, $el.dataset.studioReviewed === 'true', $el.dataset.notes)" 
                    class="px-4 py-1.5 border border-emerald-500 text-emerald-600 hover:bg-emerald-50 rounded text-xs font-bold flex items-center gap-1 transition shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Buka
                </button>
            </div>
            @empty
            <div class="text-center py-10">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p class="text-sm font-extrabold text-gray-400">Belum ada hasil foto yang tersedia.</p>
                <p class="text-xs font-medium text-gray-400 mt-1">Hasil foto akan muncul di sini setelah sesi selesai dan diedit.</p>
            </div>
            @endforelse

        </div>
    </div>

    <!-- Modals System -->
    
    <!-- 1. Result Details Modal (Image 4) -->
    <div x-show="activeModal === 'result'" x-cloak class="relative z-50">
        <div x-show="activeModal === 'result'" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeModal"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="activeModal === 'result'" x-transition 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md flex flex-col max-h-[90vh]">
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                        <h3 class="text-sm font-extrabold text-gray-800">Buka Hasil Foto : <span x-text="selectedResult"></span></h3>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-600 border border-gray-200 rounded-full p-1 hover:bg-gray-50 transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 overflow-y-auto">
                        <div class="w-full h-40 bg-gray-100 rounded-xl mb-6 overflow-hidden shrink-0">
                            <img :src="selectedImage" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="w-full bg-white border border-gray-200 rounded-xl p-4 shadow-sm text-center mb-4 relative">
                            <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-white px-2 text-[10px] font-bold text-gray-400">Link Hasil Foto :</span>
                            <p class="text-xs font-extrabold text-gray-800 underline underline-offset-2 decoration-gray-300 break-all" x-text="selectedResultLink"></p>
                        </div>
                        <p class="text-[11px] font-bold text-gray-400 mb-6 text-center">Folder berisi seluruh hasil foto resolusi penuh</p>
                        
                        <a :href="selectedResultLink" target="_blank" class="w-full py-3 bg-[#22C55E] hover:bg-green-600 text-white text-xs font-extrabold rounded-xl shadow-md shadow-green-500/20 transition flex items-center justify-center gap-2 mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Buka Tautan
                        </a>

                        <template x-if="selectedResultNotes">
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                                <p class="text-[10px] font-bold text-blue-400 mb-1 uppercase tracking-wider">Catatan dari Kami</p>
                                <p class="text-xs text-blue-800 font-medium leading-relaxed whitespace-pre-wrap" x-text="selectedResultNotes"></p>
                            </div>
                        </template>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                            <div>
                                <h4 class="text-sm font-extrabold text-gray-800">Ulasan sesi ini</h4>
                                <template x-if="assignments.filter(a => !a.has_reviewed).length > 0 || !hasReviewedStudio">
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-amber-100 text-amber-600 text-[10px] font-extrabold rounded-md border border-amber-200">Belum diulas semua</span>
                                </template>
                                <template x-if="assignments.filter(a => !a.has_reviewed).length === 0 && hasReviewedStudio">
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-600 text-[10px] font-extrabold rounded-md border border-green-200">Selesai diulas</span>
                                </template>
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mt-4 space-y-4">
                            <p class="text-[10px] font-bold text-gray-500 mb-2 text-center">Setelah mengunduh foto, berikan ulasan untuk fotografer Anda.</p>
                            
                            <template x-for="assignment in assignments" :key="assignment.employee_id">
                                <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 shrink-0 shadow-sm">
                                            <img :src="assignment.avatar ? assignment.avatar : 'https://ui-avatars.com/api/?background=2B5488&color=fff&name=' + encodeURIComponent(assignment.name)" alt="Avatar" class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-left">
                                            <h5 class="text-[11px] font-extrabold text-gray-800" x-text="assignment.name"></h5>
                                            <p class="text-[9px] font-bold text-gray-400">Fotografer</p>
                                        </div>
                                    </div>
                                    <template x-if="!assignment.has_reviewed">
                                        <button @click="openRating(assignment.employee_id, assignment.name, assignment.avatar)" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-[11px] font-extrabold rounded-lg hover:bg-gray-50 transition flex items-center gap-1 shadow-sm shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                            Beri Ulasan
                                        </button>
                                    </template>
                                    <template x-if="assignment.has_reviewed">
                                        <span class="px-3 py-1.5 bg-gray-50 border border-gray-200 text-gray-400 text-[11px] font-extrabold rounded-lg flex items-center gap-1 shadow-sm shrink-0 cursor-not-allowed">
                                            <svg class="w-3.5 h-3.5 fill-amber-400 text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Sudah Diulas
                                        </span>
                                    </template>
                                </div>
                            </template>
                            
                            <!-- Studio Rating Box -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-100 shadow-sm mt-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 shadow-sm border border-gray-100 bg-white">
                                        <img src="{{ asset('images/logo.PNG') }}" alt="Imako Studio" class="w-full h-full object-contain p-1">
                                    </div>
                                    <div class="text-left">
                                        <h5 class="text-[11px] font-extrabold text-gray-800">Imako Studio</h5>
                                        <p class="text-[9px] font-bold text-gray-400">Studio Foto</p>
                                    </div>
                                </div>
                                <template x-if="!hasReviewedStudio">
                                    <button @click="openRating(null, 'Imako Studio', '{{ asset('images/logo.PNG') }}')" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-[11px] font-extrabold rounded-lg hover:bg-gray-50 transition flex items-center gap-1 shadow-sm shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        Beri Ulasan
                                    </button>
                                </template>
                                <template x-if="hasReviewedStudio">
                                    <span class="px-3 py-1.5 bg-gray-50 border border-gray-200 text-gray-400 text-[11px] font-extrabold rounded-lg flex items-center gap-1 shadow-sm shrink-0 cursor-not-allowed">
                                        <svg class="w-3.5 h-3.5 fill-amber-400 text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Sudah Diulas
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Rating Modal (Image 1/3) -->
    <div x-show="activeModal === 'rating'" x-cloak class="relative z-50">
        <div x-show="activeModal === 'rating'" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="activeModal = 'result'"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="activeModal === 'rating'" x-transition 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md p-8">
                    
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 shadow-sm border border-gray-100 bg-white">
                            <img :src="selectedPhotographerAvatar ? selectedPhotographerAvatar : 'https://ui-avatars.com/api/?background=2B5488&color=fff&name=' + encodeURIComponent(selectedPhotographerName)" alt="Avatar" class="w-full h-full object-cover" :class="selectedEmployeeId === null ? 'p-1 object-contain' : ''">
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-800" x-text="selectedPhotographerName"></h3>
                            <p class="text-xs font-bold text-gray-400" x-text="selectedEmployeeId === null ? 'Studio Foto' : 'Fotografer'"></p>
                        </div>
                    </div>

                    <!-- Rating Keseluruhan -->
                    <div class="mb-8 text-center border-b border-gray-100 pb-8">
                        <p class="text-xs font-bold text-gray-500 mb-4">Rating Keseluruhan</p>
                        <div class="flex items-center justify-center gap-2">
                            <template x-for="i in 5">
                                <button @click="setRating('overall', i)" @mouseenter="hoverRating('overall', i)" @mouseleave="leaveRating('overall')" class="focus:outline-none transition-transform hover:scale-110">
                                    <svg class="w-8 h-8 transition-colors drop-shadow-sm" :class="(hoverRatings.overall >= i || (!hoverRatings.overall && ratings.overall >= i)) ? 'text-amber-500 fill-amber-500' : 'text-gray-200 fill-gray-200'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Category Ratings -->
                    <div class="flex flex-col gap-5 mb-8">
                        <!-- Ketepatan Waktu -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Ketepatan waktu</span>
                            <div class="flex items-center gap-1">
                                <template x-for="i in 5">
                                    <button @click="setRating('timing', i)" @mouseenter="hoverRating('timing', i)" @mouseleave="leaveRating('timing')" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-5 h-5 transition-colors drop-shadow-sm" :class="(hoverRatings.timing >= i || (!hoverRatings.timing && ratings.timing >= i)) ? 'text-amber-500 fill-amber-500' : 'text-gray-200 fill-gray-200'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <!-- Profesionalisme -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Profesionalisme</span>
                            <div class="flex items-center gap-1">
                                <template x-for="i in 5">
                                    <button @click="setRating('professionalism', i)" @mouseenter="hoverRating('professionalism', i)" @mouseleave="leaveRating('professionalism')" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-5 h-5 transition-colors drop-shadow-sm" :class="(hoverRatings.professionalism >= i || (!hoverRatings.professionalism && ratings.professionalism >= i)) ? 'text-amber-500 fill-amber-500' : 'text-gray-200 fill-gray-200'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <!-- Hasil Kerja -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Hasil Kerja</span>
                            <div class="flex items-center gap-1">
                                <template x-for="i in 5">
                                    <button @click="setRating('quality', i)" @mouseenter="hoverRating('quality', i)" @mouseleave="leaveRating('quality')" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-5 h-5 transition-colors drop-shadow-sm" :class="(hoverRatings.quality >= i || (!hoverRatings.quality && ratings.quality >= i)) ? 'text-amber-500 fill-amber-500' : 'text-gray-200 fill-gray-200'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <!-- Komunikasi -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Komunikasi</span>
                            <div class="flex items-center gap-1">
                                <template x-for="i in 5">
                                    <button @click="setRating('communication', i)" @mouseenter="hoverRating('communication', i)" @mouseleave="leaveRating('communication')" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-5 h-5 transition-colors drop-shadow-sm" :class="(hoverRatings.communication >= i || (!hoverRatings.communication && ratings.communication >= i)) ? 'text-amber-500 fill-amber-500' : 'text-gray-200 fill-gray-200'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 mb-2 text-left">Komentar (opsional)</label>
                        <textarea x-model="notes" :placeholder="selectedEmployeeId === null ? 'Bagaimana pengalaman kamu ketika foto di Imako Studio ...' : 'Ceritakan pengalaman Anda Bersama fotografer ini ...'" rows="4" class="w-full border-2 border-gray-100 rounded-xl p-4 text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-blue transition resize-none"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button @click="activeModal = 'result'" class="flex-1 py-3 bg-white border border-gray-300 text-gray-700 font-extrabold text-[11px] rounded-lg hover:bg-gray-50 transition shadow-sm">Lewati</button>
                        <button @click="submitRating" class="flex-1 py-3 bg-blue-600 border border-transparent text-white font-extrabold text-[11px] rounded-lg hover:bg-blue-700 transition shadow-sm relative">
                            <span x-show="!isSubmitting">Kirim Ulasan</span>
                            <span x-show="isSubmitting" x-cloak>
                                <svg class="animate-spin h-4 w-4 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 3. Success Modal (Image 2) -->
    <div x-show="activeModal === 'success'" x-cloak class="relative z-50">
        <div x-show="activeModal === 'success'" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="activeModal === 'success'" x-transition 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-center shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-[400px] p-10">
                    
                    <!-- Close Button -->
                    <button @click="closeModal" type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 border border-gray-200 rounded-full p-1 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="w-20 h-20 bg-[#86EFAC]/40 rounded-full flex items-center justify-center mx-auto mb-6">
                        <div class="w-14 h-14 bg-[#4ADE80] rounded-full flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    
                    <h2 class="text-[17px] font-extrabold text-gray-800 mb-2">Terima Kasih!</h2>
                    <p class="text-[11px] font-bold text-gray-400 mb-6">No. Booking: <span class="text-gray-800" x-text="'#IMK-' + selectedBookingCode"></span></p>
                    
                    <p class="text-[11px] font-bold text-gray-400 mb-10 max-w-sm mx-auto leading-relaxed">
                        Terima kasih atas ulasan Anda untuk <span class="text-gray-800" x-text="assignments.map(a => a.name).join(', ')"></span>.<br/>Terima kasih banyak sudah mempercayai Imako Studio!
                    </p>

                    <button @click="activeModal = 'result'" class="inline-block px-6 py-3 bg-[#1E40AF] text-white font-extrabold text-[11px] rounded-xl hover:bg-blue-900 transition shadow-md shadow-blue-500/20">
                        Lihat Hasil Foto
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function resultsView() {
        return {
            activeModal: null,
            selectedResult: '',
            selectedImage: '',
            assignments: [],
            selectedResultLink: '',
            selectedBookingCode: '',
            selectedResultNotes: '',
            selectedBookingId: null,
            selectedEmployeeId: null,
            selectedPhotographerName: '',
            selectedPhotographerAvatar: null,
            hasReviewedStudio: false,
            isSubmitting: false,
            hasSubmittedAnyReview: false,
            
            ratings: {
                overall: 0,
                timing: 0,
                professionalism: 0,
                quality: 0,
                communication: 0
            },
            hoverRatings: {
                overall: 0,
                timing: 0,
                professionalism: 0,
                quality: 0,
                communication: 0
            },
            notes: '',

            openResult(bookingId, name, image, assignments, resultLink, bookingCode, hasReviewedStudio, resultNotes) {
                this.selectedBookingId = bookingId;
                this.selectedResult = name;
                this.selectedImage = image;
                this.assignments = assignments;
                this.selectedResultLink = resultLink;
                this.selectedBookingCode = bookingCode;
                this.selectedResultNotes = resultNotes;
                this.hasReviewedStudio = hasReviewedStudio;
                
                // Cek apakah ada fotografer yang belum diulas
                const unrated = this.assignments.filter(a => !a.has_reviewed);
                if (unrated.length > 0) {
                    this.openRating(unrated[0].employee_id, unrated[0].name, unrated[0].avatar);
                } else if (!this.hasReviewedStudio) {
                    this.openRating(null, 'Imako Studio', '{{ asset('images/logo.PNG') }}');
                } else {
                    this.activeModal = 'result';
                }
            },

            openRating(employeeId, employeeName, employeeAvatar) {
                this.selectedEmployeeId = employeeId;
                this.selectedPhotographerName = employeeName;
                this.selectedPhotographerAvatar = employeeAvatar;
                this.notes = '';
                this.ratings = { overall: 0, timing: 0, professionalism: 0, quality: 0, communication: 0 };
                this.activeModal = 'rating';
            },

            async submitRating() {
                if (this.ratings.overall === 0) {
                    alert('Silakan pilih rating keseluruhan minimal 1 bintang.');
                    return;
                }
                
                this.isSubmitting = true;
                try {
                    const response = await fetch(`/user/reviews/${this.selectedBookingId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            employee_id: this.selectedEmployeeId,
                            rating: this.ratings.overall,
                            comment: this.notes
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        this.hasSubmittedAnyReview = true;
                        if (this.selectedEmployeeId === null) {
                            this.hasReviewedStudio = true;
                        } else {
                            // Mark current assignment as reviewed
                            const currentAssignment = this.assignments.find(a => a.employee_id === this.selectedEmployeeId);
                            if (currentAssignment) currentAssignment.has_reviewed = true;
                        }
                        
                        // Check if there are more unrated employees
                        const unrated = this.assignments.filter(a => !a.has_reviewed);
                        if (unrated.length > 0) {
                            // Automatically go to next unrated
                            this.openRating(unrated[0].employee_id, unrated[0].name, unrated[0].avatar);
                        } else if (!this.hasReviewedStudio) {
                            // Automatically go to studio rating
                            this.openRating(null, 'Imako Studio', '{{ asset('images/logo.PNG') }}');
                        } else {
                            // All rated! Show Success modal
                            this.activeModal = 'success';
                        }
                    } else {
                        alert(data.message || 'Gagal mengirim ulasan');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                } finally {
                    this.isSubmitting = false;
                }
            },

            closeModal() {
                if (this.hasSubmittedAnyReview) {
                    window.location.reload();
                } else {
                    this.activeModal = null;
                }
            },

            setRating(category, val) {
                this.ratings[category] = val;
            },

            hoverRating(category, val) {
                this.hoverRatings[category] = val;
            },
            
            leaveRating(category) {
                this.hoverRatings[category] = 0;
            }
        }
    }
</script>
@endsection
