        <!-- Tab 3: Notifikasi -->
        <form id="tab-notifikasi" class="hidden" action="{{ route('admin.notification-settings.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-8">
                
                <!-- Left Col: Notifikasi Website -->
                <div class="space-y-4">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Notifikasi Website</h4>

                    <!-- Suara Notifikasi -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl mb-4">
                        <label class="block text-xs font-extrabold text-brand-dark mb-1">Suara Custom Notifikasi</label>
                        <p class="text-[10px] font-bold text-gray-400 mb-3">Pilih suara notifikasi saat website terbuka</p>
                        <div class="relative">
                            <select name="notification_sound" class="w-full appearance-none bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg pl-4 pr-10 py-2.5 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary outline-none transition cursor-pointer shadow-sm">
                                <option {{ ($notifPrefs['notification_sound'] ?? '') == 'Ting (Default)' ? 'selected' : '' }}>Ting (Default)</option>
                                <option {{ ($notifPrefs['notification_sound'] ?? '') == 'Bell Ring' ? 'selected' : '' }}>Bell Ring</option>
                                <option {{ ($notifPrefs['notification_sound'] ?? '') == 'Pop Mellow' ? 'selected' : '' }}>Pop Mellow</option>
                                <option {{ ($notifPrefs['notification_sound'] ?? '') == 'Circles' ? 'selected' : '' }}>Circles</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hidden inputs moved outside labels to prevent click interception -->
                    <input type="hidden" name="booking_baru" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Booking baru masuk</h5>
                            <p class="text-[10px] font-bold text-gray-400">Setiap ada reservasi baru</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="booking_baru" value="1" class="sr-only peer" {{ ($notifPrefs['booking_baru'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="pembayaran_diterima" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Pembayaran diterima</h5>
                            <p class="text-[10px] font-bold text-gray-400">Bukti transfer diunggah pelanggan</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="pembayaran_diterima" value="1" class="sr-only peer" {{ ($notifPrefs['pembayaran_diterima'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="booking_dibatalkan" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Booking dibatalkan</h5>
                            <p class="text-[10px] font-bold text-gray-400">Pelanggan membatalkan reservasi</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="booking_dibatalkan" value="1" class="sr-only peer" {{ ($notifPrefs['booking_dibatalkan'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="pengingat_sesi" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Pengingat sesi hari ini</h5>
                            <p class="text-[10px] font-bold text-gray-400">Dikirim pagi hari sebelum sesi dimulai</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="pengingat_sesi" value="1" class="sr-only peer" {{ ($notifPrefs['pengingat_sesi'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="laporan_mingguan" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Laporan mingguan</h5>
                            <p class="text-[10px] font-bold text-gray-400">Ringkasan kinerja & pemasukan</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="laporan_mingguan" value="1" class="sr-only peer" {{ ($notifPrefs['laporan_mingguan'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="pegawai_baru" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Pegawai baru terdaftar</h5>
                            <p class="text-[10px] font-bold text-gray-400">Akun pegawai baru dibuat</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="pegawai_baru" value="1" class="sr-only peer" {{ ($notifPrefs['pegawai_baru'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>
                </div>

                <!-- Right Col: Notifikasi Push / In-App & Laporan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-extrabold text-brand-dark mb-4">Notifikasi Push / In-App</h4>
                    
                    <input type="hidden" name="notifikasi_realtime" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Notifikasi real-time</h5>
                            <p class="text-[10px] font-bold text-gray-400">Popup langsung di browser</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="notifikasi_realtime" value="1" class="sr-only peer" {{ ($notifPrefs['notifikasi_realtime'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="validasi_menunggu" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Validasi pembayaran menunggu</h5>
                            <p class="text-[10px] font-bold text-gray-400">Pengingat jika ada yang belum divalidasi</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="validasi_menunggu" value="1" class="sr-only peer" {{ ($notifPrefs['validasi_menunggu'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="hasil_belum_dikirim" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Hasil foto belum dikirim</h5>
                            <p class="text-[10px] font-bold text-gray-400">Pegawai belum kirim link ke klien</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="hasil_belum_dikirim" value="1" class="sr-only peer" {{ ($notifPrefs['hasil_belum_dikirim'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <input type="hidden" name="sesi_akan_dimulai" value="0">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition mb-6">
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Sesi akan dimulai (30 mnt)</h5>
                            <p class="text-[10px] font-bold text-gray-400">Pengingat 30 menit sebelum sesi</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="sesi_akan_dimulai" value="1" class="sr-only peer" {{ ($notifPrefs['sesi_akan_dimulai'] ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[120%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-dark"></div>
                        </div>
                    </label>

                    <h4 class="text-xs font-extrabold text-brand-dark mb-4 mt-8">Frekuensi Laporan Otomatis</h4>
                    
                    <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <input type="radio" name="report_freq" value="harian" class="w-4 h-4 text-brand-dark bg-gray-100 border-gray-300 focus:ring-brand-dark" {{ ($notifPrefs['report_freq'] ?? 'harian') == 'harian' ? 'checked' : '' }}>
                        </div>
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Harian</h5>
                            <p class="text-[10px] font-bold text-gray-400">Ringkasan dikirim setiap pukul 20.00</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <input type="radio" name="report_freq" value="mingguan" class="w-4 h-4 text-brand-dark bg-gray-100 border-gray-300 focus:ring-brand-dark" {{ ($notifPrefs['report_freq'] ?? '') == 'mingguan' ? 'checked' : '' }}>
                        </div>
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Mingguan</h5>
                            <p class="text-[10px] font-bold text-gray-400">Setiap Senin pagi</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <input type="radio" name="report_freq" value="bulanan" class="w-4 h-4 text-brand-dark bg-gray-100 border-gray-300 focus:ring-brand-dark" {{ ($notifPrefs['report_freq'] ?? '') == 'bulanan' ? 'checked' : '' }}>
                        </div>
                        <div>
                            <h5 class="text-xs font-extrabold text-brand-dark mb-0.5">Bulanan</h5>
                            <p class="text-[10px] font-bold text-gray-400">Tanggal 1 setiap bulan</p>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 items-center">
                @if(session('status') === 'notifikasi-updated')
                    <div class="mr-auto p-2 bg-green-50 text-green-600 rounded-lg text-xs font-bold text-center">
                        Preferensi notifikasi berhasil disimpan.
                    </div>
                @endif
                <button type="submit" class="px-5 py-2 bg-brand-dark text-white text-[11px] font-extrabold rounded-lg hover:bg-brand-primary transition shadow-sm">
                    Simpan Preferensi
                </button>
            </div>
        </form>
