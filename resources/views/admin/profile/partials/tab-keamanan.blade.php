        <!-- Tab 4: Keamanan -->
        <form id="tab-keamanan" class="hidden" action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-8">
                
                <!-- Left Col: Ubah Password -->
                <div class="space-y-5">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Ubah Password</h4>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Password saat ini</label>
                        <input type="password" name="current_password" placeholder="Masukkan password lama" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Password baru</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm" required>
                        <p class="text-[9px] font-bold text-gray-400 mt-1.5">Gunakan kombinasi huruf, angka, dan simbol agar lebih aman.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Konfirmasi password baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg px-4 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition shadow-sm" required>
                    </div>
                </div>

                <!-- Right Col: Keamanan Tambahan -->
                <div class="space-y-6">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Keamanan Tambahan</h4>
                    
                    <!-- 2FA -->
                    <div class="p-5 border border-gray-200 rounded-xl bg-gray-50/50">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div>
                                <h5 class="text-xs font-extrabold text-brand-dark mb-1">Autentikasi 2 Langkah (2FA)</h5>
                                <p class="text-[10px] font-bold text-gray-400 leading-relaxed">Tambahkan lapisan keamanan ekstra ke akun Anda. Saat login, Anda akan diminta memasukkan kode unik dari perangkat Anda.</p>
                            </div>
                            <div class="relative shrink-0 mt-1">
                                <input type="checkbox" class="sr-only peer" {{ auth()->user()->google2fa_secret ? 'checked' : '' }} disabled>
                                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                            </div>
                        </div>
                        
                        @if(!auth()->user()->google2fa_secret)
                            <a href="{{ route('2fa.index') }}" class="inline-block px-4 py-2 bg-white border border-gray-200 text-brand-dark text-[10px] font-extrabold rounded-lg hover:bg-gray-50 transition shadow-sm w-full md:w-auto text-center">
                                Konfigurasi 2FA
                            </a>
                        @else
                            <button type="submit" form="disable-2fa-form" class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 text-[10px] font-extrabold rounded-lg transition shadow-sm w-full md:w-auto">
                                Nonaktifkan 2FA
                            </button>
                        @endif
                    </div>

                    <!-- Sesi Aktif -->
                    <div>
                        <h5 class="text-[11px] font-extrabold text-brand-dark mb-3">Sesi Perangkat Aktif</h5>
                        <div class="space-y-3" id="active-sessions-container">
                            @include('admin.profile.partials.tab-keamanan-sessions')
                        </div>
                        
                        <div id="logout-other-devices-btn-container">
                            @if(isset($sessions) && count($sessions) > 1)
                            <button type="button" onclick="document.getElementById('logout-other-browser-sessions-form').submit();" class="mt-4 px-4 py-2 bg-white border border-gray-200 text-brand-dark text-[10px] font-extrabold rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center justify-center gap-2 w-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout dari perangkat lain
                            </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 items-center">
                @if(session('status') === 'password-updated')
                    <div class="mr-auto p-2 bg-green-50 text-green-600 rounded-lg text-xs font-bold text-center">
                        Password berhasil diperbarui.
                    </div>
                @endif
                <button type="submit" class="px-5 py-2 bg-brand-dark text-white text-[11px] font-extrabold rounded-lg hover:bg-brand-primary transition shadow-sm">
                    Update Keamanan
                </button>
            </div>
        </form>

        <form id="disable-2fa-form" action="{{ route('2fa.disable') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <form id="logout-other-browser-sessions-form" action="{{ route('profile.sessions.destroy') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <form id="logout-specific-session-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
