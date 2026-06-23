        <!-- Tab 1: Profil & Akun -->
        <div id="tab-profil">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-8">
                
                <!-- Left Col: Informasi Pribadi -->
                <div class="space-y-5">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Informasi Pribadi</h4>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Nomor HP</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Bio singkat</label>
                        <textarea name="bio" rows="3" placeholder="Tulis sedikit tentang diri Anda..." class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm resize-none">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>

                <!-- Right Col: Preferensi Akun -->
                <div class="space-y-5">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Preferensi Akun</h4>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Bahasa tampilan</label>
                        <div class="relative">
                            <select class="w-full appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                                <option>Bahasa Indonesia</option>
                                <option>English</option>
                            </select>
                            
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Zona waktu</label>
                        <div class="relative">
                            <select class="w-full appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                                <option>WIB (GMT+7) — Jakarta</option>
                                <option>WITA (GMT+8) — Makassar</option>
                                <option>WIT (GMT+9) — Jayapura</option>
                            </select>
                            
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Format tanggal</label>
                        <div class="relative">
                            <select class="w-full appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                                <option>DD/MM/YYYY</option>
                                <option>MM/DD/YYYY</option>
                            </select>
                            
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Mata uang</label>
                        <input type="text" value="IDR — Rupiah (Rp)" disabled class="w-full bg-gray-50 border border-gray-200 text-gray-400 text-xs font-bold rounded-lg px-4 py-2.5 shadow-sm cursor-not-allowed">
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 items-center">
                @if(session('status') === 'profile-updated')
                    <div class="mr-auto p-2 bg-green-50 text-green-600 rounded-lg text-xs font-bold text-center">
                        Profil berhasil diperbarui.
                    </div>
                @endif
                <button type="reset" class="px-5 py-2 bg-white border border-gray-200 text-gray-500 text-[11px] font-extrabold rounded-lg hover:bg-gray-50 transition shadow-sm">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 bg-brand-dark text-white text-[11px] font-extrabold rounded-lg hover:bg-brand-primary transition shadow-sm flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
        </div>
