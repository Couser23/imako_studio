        <!-- Tab 2: Info Studio -->
        <form id="tab-info" class="hidden" action="{{ route('admin.studio-settings.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-8">
                
                <!-- Left Col: Identitas Studio -->
                <div class="space-y-5">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Identitas Studio</h4>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Nama studio</label>
                        <input type="text" name="name" value="{{ old('name', $studioSetting->name) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $studioSetting->tagline) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Alamat lengkap</label>
                        <textarea name="address" rows="3" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm resize-none">{{ old('address', $studioSetting->address) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Telepon studio</label>
                            <input type="text" name="phone" value="{{ old('phone', $studioSetting->phone) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1.5">WhatsApp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $studioSetting->whatsapp) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Email studio</label>
                        <input type="email" name="email" value="{{ old('email', $studioSetting->email) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                    </div>
                </div>

                <!-- Right Col: Jam Operasional & Pengaturan Booking -->
                <div class="space-y-5">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Jam Operasional & Pengaturan Booking</h4>
                    
                    <!-- Jam buka tutup -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Jam buka</label>
                            <div class="relative clockpicker" data-autoclose="true">
                                <input type="text" name="open_time" id="jam_buka" value="{{ old('open_time', \Carbon\Carbon::parse($studioSetting->open_time)->format('H:i')) }}" readonly class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg pl-4 pr-10 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm cursor-pointer bg-white">
                                
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Jam tutup</label>
                            <div class="relative clockpicker" data-autoclose="true">
                                <input type="text" name="close_time" id="jam_tutup" value="{{ old('close_time', \Carbon\Carbon::parse($studioSetting->close_time)->format('H:i')) }}" readonly class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg pl-4 pr-10 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm cursor-pointer bg-white">
                                
                            </div>
                        </div>
                    </div>

                    <!-- Hari operasional -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Hari operasional</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $opDays = $studioSetting->operational_days ?? [];
                            @endphp
                            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                                <input type="checkbox" name="operational_days[]" value="{{ $day }}" id="day_{{ $day }}" class="hidden" {{ in_array($day, $opDays) ? 'checked' : '' }}>
                                <label for="day_{{ $day }}" onclick="toggleDayUI(this, 'day_{{ $day }}')" class="cursor-pointer w-10 h-8 rounded-lg {{ in_array($day, $opDays) ? 'bg-brand-dark text-white shadow-sm' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }} text-[10px] font-extrabold flex items-center justify-center transition">
                                    {{ $day }}
                                </label>
                            @endforeach
                            <button type="button" onclick="openCloseStudioModal()" class="px-3 h-8 rounded-lg bg-red-600/90 hover:bg-red-600 text-white text-[10px] font-extrabold flex items-center gap-1.5 transition shadow-sm ml-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                Close
                            </button>
                        </div>
                    </div>

                    <!-- Min/Max booking -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Minimal booking sebelum sesi (jam)</label>
                        <div class="relative">
                            <select name="min_booking_hours" class="w-full appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                                <option value="0" {{ $studioSetting->min_booking_hours == 0 ? 'selected' : '' }}>Tidak ada (Bisa booking langsung)</option>
                                <option value="3" {{ $studioSetting->min_booking_hours == 3 ? 'selected' : '' }}>3 jam sebelumnya</option>
                                <option value="6" {{ $studioSetting->min_booking_hours == 6 ? 'selected' : '' }}>6 jam sebelumnya</option>
                                <option value="24" {{ $studioSetting->min_booking_hours == 24 ? 'selected' : '' }}>24 jam sebelumnya</option>
                            </select>
                            
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Maksimal booking ke depan (bulan)</label>
                        <div class="relative">
                            <select name="max_booking_months" class="w-full appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                                <option value="1" {{ $studioSetting->max_booking_months == 1 ? 'selected' : '' }}>1 bulan</option>
                                <option value="3" {{ $studioSetting->max_booking_months == 3 ? 'selected' : '' }}>3 bulan</option>
                                <option value="6" {{ $studioSetting->max_booking_months == 6 ? 'selected' : '' }}>6 bulan</option>
                            </select>
                            
                        </div>
                    </div>

                    <!-- Media Sosial -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Media Sosial</label>
                        <div class="space-y-2">
                            <!-- IG -->
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-lg bg-pink-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </div>
                                <input type="text" name="instagram" value="{{ old('instagram', $studioSetting->instagram) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                            </div>
                            <!-- FB -->
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </div>
                                <input type="text" name="facebook" value="{{ old('facebook', $studioSetting->facebook) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                            </div>
                            <!-- WA -->
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </div>
                                <input type="text" name="whatsapp_link" value="{{ old('whatsapp_link', $studioSetting->whatsapp_link) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 items-center">
                @if(session('status') === 'studio-info-updated')
                    <div class="mr-auto p-2 bg-green-50 text-green-600 rounded-lg text-xs font-bold text-center">
                        Informasi studio berhasil diperbarui.
                    </div>
                @endif
                <button type="button" onclick="document.getElementById('tab-info').reset()" class="px-5 py-2 bg-white border border-gray-200 text-gray-500 text-[11px] font-extrabold rounded-lg hover:bg-gray-50 transition shadow-sm">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 bg-brand-dark text-white text-[11px] font-extrabold rounded-lg hover:bg-brand-primary transition shadow-sm flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
