@extends('layouts.user', ['title' => 'Booking Baru'])

@section('content')
<div class="mb-6 -mt-4">
    <p class="text-sm text-gray-400 font-medium">Pilih paket, jadwal dan konfirmasi pesanan</p>
</div>

<div x-data="bookingForm()">
    
<form action="{{ route('user.bookings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="package_id" :value="selectedPackage">
    <input type="hidden" name="booking_date" :value="`${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`">
    <input type="hidden" name="start_time" :value="selectedTime">
    <input type="hidden" name="customer_name" :value="customerName">
    <input type="hidden" name="customer_phone" :value="customerPhone">
    <input type="hidden" name="notes" :value="customerNotes">

    <!-- Stepper (Hidden on Step 5) -->
    <div x-show="step < 5" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between max-w-3xl mx-auto relative">
            <!-- Connecting Line -->
            <div class="absolute top-1/2 left-0 w-full h-[2px] bg-gray-100 -z-10 -translate-y-1/2"></div>
            
            <!-- Step 1 -->
            <div class="flex flex-col items-center gap-2 bg-white px-4">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                     :class="step >= 1 ? 'bg-brand-blue text-white shadow-md shadow-brand-blue/30' : 'bg-gray-100 text-gray-400'">
                     <svg x-show="step > 1" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                     <span x-show="step == 1">1</span>
                </div>
                <span class="text-xs font-bold" :class="step >= 1 ? 'text-gray-800' : 'text-gray-400'">Paket</span>
            </div>
            
            <!-- Step 2 -->
            <div class="flex flex-col items-center gap-2 bg-white px-4">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                     :class="step >= 2 ? 'bg-brand-blue text-white shadow-md shadow-brand-blue/30' : 'bg-gray-100 text-gray-400'">
                     <svg x-show="step > 2" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                     <span x-show="step <= 2">2</span>
                </div>
                <span class="text-xs font-bold" :class="step >= 2 ? 'text-gray-800' : 'text-gray-400'">Jadwal</span>
            </div>
            
            <!-- Step 3 -->
            <div class="flex flex-col items-center gap-2 bg-white px-4">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                     :class="step >= 3 ? 'bg-brand-blue text-white shadow-md shadow-brand-blue/30' : 'bg-gray-100 text-gray-400'">
                     <svg x-show="step > 3" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                     <span x-show="step <= 3">3</span>
                </div>
                <span class="text-xs font-bold" :class="step >= 3 ? 'text-gray-800' : 'text-gray-400'">Konfirmasi</span>
            </div>
            
            <!-- Step 4 -->
            <div class="flex flex-col items-center gap-2 bg-white px-4">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                     :class="step >= 4 ? 'bg-brand-blue text-white shadow-md shadow-brand-blue/30' : 'bg-gray-100 text-gray-400'">
                     <span x-show="step <= 4">4</span>
                </div>
                <span class="text-xs font-bold" :class="step >= 4 ? 'text-gray-800' : 'text-gray-400'">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- STEP 1: PAKET -->
    <div x-show="step === 1" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[500px] flex flex-col justify-between">
        <div>
            <div class="mb-6">
                <h2 class="text-xl font-extrabold text-brand-dark">Pilih Paket Foto</h2>
                <p class="text-sm text-gray-400 font-medium">Klik paket untuk memilih, lalu lanjut ke pilih jadwal</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <template x-for="pkg in packages" :key="pkg.id">
                    <label 
                        class="relative block rounded-2xl overflow-hidden cursor-pointer transition-all duration-200 h-[220px]"
                        :style="selectedPackage == pkg.id ? 'border: 4px solid #0f172a;' : 'border: 4px solid transparent;'"
                    >
                        <input type="radio" :value="pkg.id" x-model.number="selectedPackage" class="sr-only">
                        
                        <!-- Background Image -->
                        <img :src="pkg.image" :alt="pkg.name" class="absolute inset-0 w-full h-full object-cover">
                        <!-- Overlay gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent"></div>
                        
                        <!-- Selection Icon -->
                        <div x-show="selectedPackage == pkg.id" x-cloak class="absolute top-4 right-4 w-7 h-7 rounded-full text-white flex items-center justify-center shadow-lg" style="background-color: #0f172a;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>

                        <!-- Content -->
                        <div class="absolute inset-0 p-6 flex flex-col justify-end text-white">
                            <h3 class="text-lg font-extrabold mb-0 drop-shadow-md" x-text="pkg.name"></h3>
                            <p x-show="pkg.subtitle" class="text-xs text-gray-200 font-medium mb-1 drop-shadow-md line-clamp-1" x-text="pkg.subtitle"></p>
                            <p class="text-xl font-extrabold text-green-400 drop-shadow-md mb-2" x-text="pkg.price"></p>
                            
                            <div class="flex items-center gap-1.5 mb-4 text-white text-xs font-semibold drop-shadow-md">
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span x-text="pkg.duration"></span>
                            </div>
                        </div>
                    </label>
                </template>
            </div>
        </div>

        <div class="flex justify-end mt-10">
            <button 
                type="button" 
                @click="step = 2"
                class="px-8 py-3 bg-brand-dark text-white font-bold rounded-lg flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-800"
                x-bind:disabled="!selectedPackage"
            >
                Lanjut: Pilih Jadwal
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>

    <!-- STEP 2: JADWAL -->
    <div x-show="step === 2" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[500px] flex flex-col justify-between">
        <div>
            <div class="mb-6">
                <h2 class="text-xl font-extrabold text-brand-dark">Pilih Tanggal & Jam</h2>
                <p class="text-sm text-gray-400 font-medium">Pilih jadwal luang dari kalender di bawah</p>
            </div>

            <!-- Calendar & Slots Container -->
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <!-- Calendar Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 w-full lg:w-[380px] shrink-0">
                    <div class="flex items-center justify-between mb-8">
                        <button type="button" @click="prevMonth" class="text-gray-400 hover:text-brand-blue transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <div class="relative inline-block text-center cursor-pointer" @click.away="showMonthPicker = false">
                            <h3 @click="showMonthPicker = !showMonthPicker" class="font-bold text-gray-800 text-sm hover:text-brand-blue transition flex items-center justify-center gap-1">
                                <span x-text="monthName + ' ' + currentYear"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </h3>
                            
                            <div x-show="showMonthPicker" x-transition x-cloak class="absolute top-full mt-2 left-1/2 -translate-x-1/2 bg-white rounded-xl shadow-lg border border-gray-100 p-4 z-10 w-64 cursor-default" @click.stop>
                                <div class="flex gap-2">
                                    <select x-model.number="currentMonth" @change="selectedDate = null; selectedTime = null" class="flex-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 py-2 px-3 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue">
                                        <template x-for="(month, index) in months" :key="index">
                                            <option :value="index" x-text="month"></option>
                                        </template>
                                    </select>
                                    <input type="number" x-model.number="currentYear" @change="selectedDate = null; selectedTime = null" class="w-24 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 py-2 px-3 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue">
                                </div>
                                <button type="button" @click="showMonthPicker = false" class="w-full mt-3 bg-brand-dark hover:bg-brand-blue text-white text-xs font-bold py-2 rounded-lg transition">Terapkan</button>
                            </div>
                        </div>
                        <button type="button" @click="nextMonth" class="text-gray-400 hover:text-brand-blue transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-y-6 mb-4 text-center">
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">SUN</div>
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">MON</div>
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">TUE</div>
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">WED</div>
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">THU</div>
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">FRI</div>
                        <div class="text-[10px] font-extrabold text-gray-400 tracking-wider">SAT</div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-y-4 text-center">
                        <template x-for="blank in blankDays" :key="'blank-'+blank">
                            <div class="w-8 h-8"></div>
                        </template>
                        <template x-for="day in days" :key="day">
                            <div class="flex justify-center">
                                <button type="button" 
                                    @click="selectedDate = day; selectedTime = null"
                                    :class="{
                                        'w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold transition-all border': true,
                                        'bg-blue-600 text-white shadow-md shadow-blue-500/30 border-blue-600': selectedDate === day,
                                        'bg-blue-50 text-brand-blue border-blue-300': selectedDate !== day && day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear(),
                                        'text-gray-700 hover:bg-gray-100 border-transparent': selectedDate !== day && !(day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear())
                                    }"
                                    x-text="day">
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Slots Selection Card -->
                <div x-show="selectedDate" x-transition x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 w-full">
                    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
                        <h4 class="font-extrabold text-gray-800">Slot tersedia - <span x-text="selectedDate + ' ' + monthName + ' ' + currentYear"></span></h4>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
                        <template x-for="slot in slots" :key="slot.time">
                            <div>
                                <!-- Available Slot -->
                                <template x-if="slot.status === 'available'">
                                    <button type="button" 
                                        @click="selectedTime = slot.time; selectedAddons = []"
                                        :class="{
                                            'w-full px-4 py-2 rounded-full font-extrabold text-sm shadow-sm transition border': true,
                                            'bg-blue-600 text-white border-blue-600': selectedTime === slot.time,
                                            'bg-white text-gray-700 border-gray-200 hover:border-blue-300': selectedTime !== slot.time
                                        }"
                                        x-text="slot.time">
                                    </button>
                                </template>
                                <!-- Full Slot -->
                                <template x-if="slot.status === 'full'">
                                    <div class="w-full text-center px-4 py-2 rounded-full bg-[#E0F2FE]/50 border border-[#BAE6FD]/60 text-cyan-700/50 font-bold text-sm line-through decoration-cyan-700/40 cursor-not-allowed" x-text="slot.time + ' penuh'">
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="bg-[#5DB0E5]/10 rounded-xl p-5 border border-[#5DB0E5]/20 inline-block px-8 py-4 w-full" :class="selectedTime ? 'bg-[#5DB0E5]' : ''">
                        <p class="text-[13px] font-extrabold text-brand-dark mb-1" :class="selectedTime ? 'text-white' : ''">Pilihan kamu : </p>
                        <p class="text-sm font-extrabold" :class="selectedTime ? 'text-white' : 'text-gray-400'">
                            <span x-show="selectedTime" x-text="selectedPackageData?.name + ' - ' + selectedDate + ' ' + monthName + ' ' + currentYear + ' - ' + selectedTime"></span>
                            <span x-show="!selectedTime">Belum memilih jam</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-10">
            <button type="button" @click="step = 1" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-lg flex items-center gap-2 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </button>
            <button type="button" @click="step = 3" :disabled="!selectedDate || !selectedTime" class="px-8 py-3 bg-brand-dark text-white font-bold rounded-lg flex items-center gap-2 transition disabled:opacity-50 hover:bg-gray-800">
                Lanjut: Konfirmasi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>

    <!-- STEP 3: KONFIRMASI -->
    <div x-show="step === 3" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[500px] flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-brand-blue mb-6">Konfirmasi Pesanan</h2>
            
            <div class="bg-[#F8FAFC] rounded-xl p-6 mb-8 max-w-2xl shadow-sm">
                <div class="flex justify-between mb-4">
                    <span class="text-sm text-gray-400 font-bold">Paket</span>
                    <span class="text-sm font-extrabold text-gray-800" x-text="selectedPackageData?.name"></span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-sm text-gray-400 font-bold">Tanggal</span>
                    <span class="text-sm font-extrabold text-gray-800" x-text="selectedDate + ' ' + monthName + ' ' + currentYear"></span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-sm text-gray-400 font-bold">Jam mulai</span>
                    <span class="text-sm font-extrabold text-gray-800" x-text="selectedTime"></span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-sm text-gray-400 font-bold">Durasi sesi</span>
                    <span class="text-sm font-extrabold text-gray-800" x-text="selectedPackageData?.duration"></span>
                </div>
                <div class="h-[2px] bg-gray-200 mb-6 mt-2"></div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-extrabold text-gray-800">Total</span>
                    <span class="text-lg font-extrabold text-green-500" x-text="'Rp ' + calculateTotal()"></span>
                </div>
            </div>
            
            <div class="max-w-2xl">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 mb-3">Add ons <span class="text-gray-400 font-medium">(opsional)</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template x-for="addon in filteredAddons" :key="addon.id">
                            <label class="flex items-center p-4 border-2 rounded-xl transition" 
                                :class="{
                                    'border-brand-blue bg-blue-50 cursor-pointer': selectedAddons.includes(addon.id) && isAddonAvailable(addon),
                                    'border-gray-100 hover:border-brand-blue cursor-pointer': !selectedAddons.includes(addon.id) && isAddonAvailable(addon),
                                    'border-gray-100 bg-gray-50 opacity-60 cursor-not-allowed': !isAddonAvailable(addon)
                                }">
                                <input type="checkbox" :value="addon.id" x-model="selectedAddons" :disabled="!isAddonAvailable(addon)" class="w-5 h-5 text-brand-blue rounded border-gray-300 focus:ring-brand-blue disabled:opacity-50">
                                <div class="ml-3 flex flex-col">
                                    <span class="text-sm font-bold text-gray-800" x-text="addon.name"></span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-extrabold text-green-500" x-text="'+Rp ' + formatRupiah(addon.price)"></span>
                                        <span x-show="!isAddonAvailable(addon)" class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded">(Waktu tidak cukup)</span>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2">Catatan khusus <span class="text-gray-400 font-medium">(opsional)</span></label>
                    <textarea name="notes" x-model="customerNotes" placeholder="contoh: sesi untuk 2 orang..." rows="3" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 focus:outline-none focus:border-brand-blue transition"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-10">
            <button type="button" @click="step = 2" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-lg flex items-center gap-2 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </button>
            <button type="button" @click="step = 4" class="px-8 py-3 bg-brand-dark text-white font-bold rounded-lg flex items-center gap-2 transition hover:bg-gray-800">
                Lanjut: Pembayaran
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>

    <!-- STEP 4: PEMBAYARAN -->
    <div x-show="step === 4" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[500px] flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-brand-blue mb-6">Upload Bukti Pembayaran</h2>
            
            <div class="max-w-2xl">
                <p class="text-sm font-bold text-gray-500 mb-3">Pilih Metode Pembayaran</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <template x-for="method in paymentMethods" :key="method.id">
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                            :class="selectedPaymentMethodId == method.id ? 'border-brand-blue bg-blue-50' : 'border-gray-100 hover:border-brand-blue'">
                            <input type="radio" name="payment_method_id" :value="method.id" x-model="selectedPaymentMethodId" class="w-5 h-5 text-brand-blue border-gray-300 focus:ring-brand-blue">
                            <div class="ml-3">
                                <span class="text-sm font-bold text-gray-800" x-text="method.name"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <div x-show="selectedPaymentMethod" x-transition x-cloak class="bg-[#F8FAFC] rounded-xl p-6 mb-8 shadow-sm">
                    <p class="text-sm font-extrabold text-brand-blue mb-6">Instruksi Pembayaran</p>

                    <!-- LOGO QRIS / Dll -->
                    <template x-if="selectedPaymentMethod?.logo">
                        <div class="mb-4">
                            <span class="text-sm text-gray-400 font-bold block mb-2">Scan QR Code / Logo</span>
                            <img :src="'/images/metode_pembayaran/' + selectedPaymentMethod.logo" class="max-w-[150px] rounded-lg border border-gray-200">
                        </div>
                    </template>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-400 font-bold">Bank / Metode</span>
                        <span class="text-sm font-extrabold text-gray-800" x-text="selectedPaymentMethod?.name"></span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-400 font-bold">No. Rekening / ID</span>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-gray-800" x-text="selectedPaymentMethod?.account_number"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-400 font-bold">Atas nama</span>
                        <span class="text-sm font-extrabold text-gray-800" x-text="selectedPaymentMethod?.account_name"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400 font-bold">Jumlah Transfer</span>
                        <span class="text-sm font-extrabold text-green-500" x-text="'Rp ' + calculateTotal()"></span>
                    </div>
                </div>

                <!-- Hidden inputs for addons -->
                <template x-for="addonId in selectedAddons" :key="addonId">
                    <input type="hidden" name="addons[]" :value="addonId">
                </template>

                <div x-show="selectedPaymentMethod?.name?.toLowerCase() !== 'tunai'">
                    <p class="text-sm font-bold text-gray-500 mb-2">Upload bukti transfer</p>
                    <label class="block border-2 border-dashed border-gray-300 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition mb-6 bg-white overflow-hidden relative" style="min-height: 180px;">
                        <input type="file" name="payment_proof" accept="image/jpeg, image/png, image/jpg" class="sr-only" @change="handleFileUpload">
                        
                        <template x-if="!paymentProofPreview">
                            <div class="py-8">
                                <svg class="w-8 h-8 text-gray-800 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <p class="text-sm font-bold text-gray-400">Klik untuk upload bukti bayar</p>
                                <p class="text-[11px] font-bold text-gray-400 mt-1">JPG, PNG - Maks 5MB</p>
                            </div>
                        </template>

                        <template x-if="paymentProofPreview">
                            <div class="absolute inset-0 w-full h-full p-2 bg-gray-50">
                                <img :src="paymentProofPreview" class="w-full h-full object-contain rounded-lg">
                            </div>
                        </template>
                    </label>
                </div>

                <div x-show="selectedPaymentMethod?.name?.toLowerCase() === 'tunai'" x-cloak class="mb-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                    <p class="text-sm text-yellow-700 font-bold">Silakan lakukan pembayaran langsung di studio pada hari-H.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-10">
            <button type="button" @click="step = 3" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-lg flex items-center gap-2 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </button>
            <button type="submit" :disabled="!selectedPaymentMethodId || (!paymentProofPreview && selectedPaymentMethod?.name?.toLowerCase() !== 'tunai')" class="px-8 py-3 bg-[#22C55E] text-white font-bold rounded-lg flex items-center gap-2 transition hover:bg-green-600 disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                <span x-text="selectedPaymentMethod?.name?.toLowerCase() === 'tunai' ? 'Selesaikan Booking' : 'Kirim Bukti Bayar & Selesai'"></span>
            </button>
        </div>
        <!-- Form Values -->
        <input type="hidden" name="package_id" :value="selectedPackage">
        <input type="hidden" name="booking_date" :value="currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(selectedDate).padStart(2, '0')">
        <input type="hidden" name="start_time" :value="selectedTime">

    </div>
</form>

<script>
    function bookingForm() {
        const serverBookings = @json($bookings ?? []);
        const serverClosedDates = @json($closedDates ?? []);

        return {
            step: {{ request()->query('package') ? 2 : 1 }},
            
            selectedPackage: {{ request()->query('package') ?: 'null' }},
            packages: [
                @foreach($packages as $pkg)
                {
                    id: {{ $pkg->id }},
                    name: @json($pkg->name),
                    subtitle: @json($pkg->description),
                    price: 'Rp {{ number_format($pkg->price, 0, ',', '.') }}',
                    duration: '{{ $pkg->duration_minutes }} menit sesi',
                    duration_minutes: {{ $pkg->duration_minutes ?? 0 }},
                    gap_minutes: {{ $pkg->gap_minutes ?? 0 }},
                    category_id: {{ $pkg->category_id ?? 'null' }},
                    image: @json($pkg->image ? asset("images/paket/" . $pkg->image) : "https://ui-avatars.com/api/?name=" . urlencode($pkg->name) . "&background=F1F5F9&color=2B5488&size=512"),
                },
                @endforeach
            ],
            
            get selectedPackageData() {
                return this.packages.find(p => p.id == this.selectedPackage) || null;
            },
            
            addons: @json($addons ?? []),
            selectedAddons: [],
            
            get filteredAddons() {
                if (!this.selectedPackageData) return [];
                return this.addons.filter(a => a.category_id === this.selectedPackageData.category_id);
            },
            
            paymentMethods: @json($paymentMethods ?? []),
            selectedPaymentMethodId: null,

            get selectedPaymentMethod() {
                return this.paymentMethods.find(p => p.id == this.selectedPaymentMethodId);
            },

            isAddonAvailable(addon) {
                if (!this.selectedDate || !this.selectedTime) return true;
                
                let currentExtra = 0;
                this.selectedAddons.forEach(id => {
                    if (id != addon.id) {
                        let a = this.addons.find(x => x.id == id);
                        if (a && a.extra_minutes) currentExtra += parseInt(a.extra_minutes);
                    }
                });
                
                let proposedExtra = currentExtra + (parseInt(addon.extra_minutes) || 0);
                
                let duration = this.selectedPackageData ? parseInt(this.selectedPackageData.duration_minutes) + parseInt(this.selectedPackageData.gap_minutes || 0) : 15;
                let totalDuration = duration + proposedExtra;
                
                let slotStartHour = parseInt(this.selectedTime.split(':')[0], 10);
                let slotStartMin = parseInt(this.selectedTime.split(':')[1], 10);
                let slotStartTotal = slotStartHour * 60 + slotStartMin;
                let slotEndTotal = slotStartTotal + totalDuration;
                
                let selectedMonthStr = String(this.currentMonth + 1).padStart(2, '0');
                let selectedDateStr = String(this.selectedDate).padStart(2, '0');
                let currentDateString = `${this.currentYear}-${selectedMonthStr}-${selectedDateStr}`;
                
                let todaysBookings = serverBookings.filter(b => {
                    let bDate = b.booking_date;
                    if (typeof bDate === 'string' && bDate.includes('T')) bDate = bDate.split('T')[0];
                    if (typeof bDate === 'string' && bDate.includes(' ')) bDate = bDate.split(' ')[0];
                    return bDate === currentDateString && b.category_id === (this.selectedPackageData ? this.selectedPackageData.category_id : null);
                });
                
                for (let b of todaysBookings) {
                    if (!b.start_time || !b.end_time) continue;
                    let bStart = b.start_time.split(':');
                    let bStartTotal = parseInt(bStart[0], 10) * 60 + parseInt(bStart[1], 10);
                    let bEnd = b.end_time.split(':');
                    let bEndTotal = parseInt(bEnd[0], 10) * 60 + parseInt(bEnd[1], 10);
                    
                    if (slotStartTotal < bEndTotal && slotEndTotal > bStartTotal) {
                        return false;
                    }
                }
                return true;
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            },

            calculateTotal() {
                let total = 0;
                if (this.selectedPackageData) {
                    // Extract numeric value from 'Rp 50.000'
                    let priceStr = this.selectedPackageData.price.replace(/[^0-9]/g, '');
                    total += parseInt(priceStr) || 0;
                }
                
                // Add selected addons price
                this.selectedAddons.forEach(id => {
                    let addon = this.addons.find(a => a.id == id);
                    if (addon) {
                        total += parseInt(addon.price) || 0;
                    }
                });
                
                return this.formatRupiah(total);
            },

            showMonthPicker: false,
            today: new Date(),
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            selectedDate: new Date().getDate(),
            selectedTime: null,
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            
            get slots() {
                if (!this.selectedDate) return [];
                
                let baseSlots = [];
                
                let openTimeStr = '{{ $settings->open_time ?? "09:00:00" }}';
                let closeTimeStr = '{{ $settings->close_time ?? "19:00:00" }}';
                
                let openHour = parseInt(openTimeStr.split(':')[0], 10);
                let closeHour = parseInt(closeTimeStr.split(':')[0], 10);
                let closeMin = parseInt(closeTimeStr.split(':')[1], 10);

                // Generate slots every 5 minutes
                for (let hour = openHour; hour <= closeHour; hour++) {
                    for (let min = 0; min < 60; min += 5) {
                        if (hour === closeHour && min > closeMin) continue;
                        let hStr = String(hour).padStart(2, '0');
                        let mStr = String(min).padStart(2, '0');
                        baseSlots.push(`${hStr}:${mStr}`);
                    }
                }
                
                // Format the selected date to match database format (YYYY-MM-DD)
                let selectedMonthStr = String(this.currentMonth + 1).padStart(2, '0');
                let selectedDateStr = String(this.selectedDate).padStart(2, '0');
                let currentDateString = `${this.currentYear}-${selectedMonthStr}-${selectedDateStr}`;

                // Check if date is in closed dates
                let isClosed = serverClosedDates.some(cd => {
                    return currentDateString >= cd.start_date.split(' ')[0] && currentDateString <= cd.end_date.split(' ')[0];
                });

                if (isClosed) {
                    return baseSlots.map((time) => ({ time: time, status: 'full' }));
                }

                // Filter bookings for the selected date and category
                let todaysBookings = serverBookings.filter(b => {
                    let bDate = b.booking_date;
                    // Handle Laravel date objects or strings
                    if (typeof bDate === 'string' && bDate.includes('T')) bDate = bDate.split('T')[0];
                    if (typeof bDate === 'string' && bDate.includes(' ')) bDate = bDate.split(' ')[0];
                    return bDate === currentDateString && b.category_id === (this.selectedPackageData ? this.selectedPackageData.category_id : null);
                });

                // Get duration from selected package (default 15 if none) including gap minutes
                let duration = this.selectedPackageData ? parseInt(this.selectedPackageData.duration_minutes) + parseInt(this.selectedPackageData.gap_minutes || 0) : 15;

                return baseSlots.map((time) => {
                    let isFull = false;
                    
                    let slotStartHour = parseInt(time.split(':')[0], 10);
                    let slotStartMin = parseInt(time.split(':')[1], 10);
                    let slotStartTotal = slotStartHour * 60 + slotStartMin;
                    
                    // Slot ends after the package duration
                    let slotEndTotal = slotStartTotal + duration;

                    // Check against existing bookings
                    for (let b of todaysBookings) {
                        if (!b.start_time || !b.end_time) continue;
                        
                        let bStart = b.start_time.split(':');
                        let bStartTotal = parseInt(bStart[0], 10) * 60 + parseInt(bStart[1], 10);
                        
                        let bEnd = b.end_time.split(':');
                        let bEndTotal = parseInt(bEnd[0], 10) * 60 + parseInt(bEnd[1], 10);

                        // Overlap condition:
                        // Slot starts before booking ends AND slot ends after booking starts
                        if (slotStartTotal < bEndTotal && slotEndTotal > bStartTotal) {
                            isFull = true;
                            break;
                        }
                    }

                    return {
                        time: time,
                        status: isFull ? 'full' : 'available'
                    };
                });
            },

            get monthName() { return this.months[this.currentMonth]; },
            get daysInMonth() { return new Date(this.currentYear, this.currentMonth + 1, 0).getDate(); },
            get blankDays() {
                let firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
                return Array.from({ length: firstDayOfMonth }, (_, i) => i);
            },
            get days() { return Array.from({ length: this.daysInMonth }, (_, i) => i + 1); },
            
            nextMonth() {
                if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; } 
                else { this.currentMonth++; }
                this.selectedDate = null; this.selectedTime = null;
            },
            prevMonth() {
                if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; } 
                else { this.currentMonth--; }
                this.selectedDate = null; this.selectedTime = null;
            },

            customerNotes: '',

            paymentProofPreview: null,
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.paymentProofPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    }
</script>
@endsection
