<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Studio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.store') }}">
                        @csrf
                        
                        <div>
                            <x-input-label for="buffer_time_minutes" :value="__('Jeda Waktu Antar-Sesi (Menit)')" />
                            <p class="text-sm text-gray-500 mb-2">Durasi waktu jeda ini akan ditambahkan secara otomatis setelah setiap jadwal sesi foto selesai. Berguna untuk waktu istirahat, persiapan alat, atau keterlambatan klien.</p>
                            
                            <x-text-input id="buffer_time_minutes" class="block mt-1 w-full max-w-md" type="number" name="buffer_time_minutes" :value="old('buffer_time_minutes', $bufferTime)" required min="0" max="120" />
                            <x-input-error :messages="$errors->get('buffer_time_minutes')" class="mt-2" />
                        </div>

                        <div class="flex items-center mt-6">
                            <x-primary-button>
                                {{ __('Simpan Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
