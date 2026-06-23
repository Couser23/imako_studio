@extends('layouts.admin')

@section('content')
<div class="px-8 py-8">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-brand-dark tracking-tight mb-2">Master Data</h1>
            <p class="text-gray-500 font-medium">Kelola Kategori Paket dan Layanan Tambahan (Add-ons).</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.packages.create') }}" class="px-6 py-3 bg-brand-dark text-white hover:bg-brand-primary text-sm font-extrabold rounded-xl transition shadow-[0_4px_14px_rgba(30,27,75,0.2)] hover:shadow-[0_6px_20px_rgba(43,84,136,0.3)] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Data Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 p-4 bg-green-50/80 border border-green-200/50 rounded-2xl flex items-center gap-4 animate-fade-in-up">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shrink-0 shadow-[0_4px_12px_rgba(34,197,94,0.3)]">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <p class="text-green-800 font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-8">
        
        <!-- Kolom Kategori -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
                <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-purple-50 text-purple-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    Daftar Kategori
                </h3>

                <div class="space-y-4">
                    @forelse($categories as $category)
                        <div class="p-4 bg-gray-50/50 rounded-xl border border-gray-100 flex items-center justify-between group hover:border-brand-primary/20 hover:bg-white hover:shadow-md transition">
                            <div>
                                <h4 class="text-sm font-extrabold text-gray-800 mb-1">{{ $category->name }}</h4>
                                <p class="text-xs font-bold text-gray-500">{{ $category->addons->count() }} Layanan Tambahan</p>
                            </div>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}')" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white flex items-center justify-center transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua add-on yang terhubung juga mungkin terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm font-bold text-gray-500">Belum ada kategori.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Addons -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50">
                <h3 class="text-sm font-extrabold text-brand-dark mb-6 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    Daftar Layanan Tambahan (Add-ons)
                </h3>

                <div class="space-y-6">
                    @forelse($categories as $category)
                        @if($category->addons->count() > 0)
                            <div>
                                <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-3 ml-2">{{ $category->name }}</h4>
                                <div class="space-y-3">
                                    @foreach($category->addons as $addon)
                                        <div class="p-4 bg-gray-50/50 rounded-xl border border-gray-100 flex items-center justify-between group hover:border-brand-primary/20 hover:bg-white hover:shadow-md transition">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-1">
                                                    <h4 class="text-sm font-extrabold text-gray-800">{{ $addon->name }}</h4>
                                                    @if($addon->extra_minutes > 0)
                                                        <span class="px-2 py-0.5 bg-brand-primary/10 text-brand-primary text-[10px] font-extrabold rounded-md">
                                                            +{{ $addon->extra_minutes }} Menit
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs font-bold text-gray-500">Rp {{ number_format($addon->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button onclick="editAddon({{ $addon->id }}, '{{ $addon->name }}', {{ $addon->price }}, {{ $addon->extra_minutes }}, {{ $category->id }})" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white flex items-center justify-center transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" onsubmit="return confirm('Hapus layanan tambahan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm font-bold text-gray-500">Belum ada layanan tambahan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="modal-edit-category" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-extrabold text-brand-dark">Edit Kategori</h3>
                <button type="button" onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="form-edit-category" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="edit_category_name" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition">
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-sm font-extrabold rounded-xl transition shadow-lg flex items-center gap-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Addon -->
<div id="modal-edit-addon" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-extrabold text-brand-dark">Edit Layanan Tambahan</h3>
                <button type="button" onclick="closeAddonModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="form-edit-addon" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 mb-2">Kategori Induk</label>
                    <select name="category_id" id="edit_addon_category" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition cursor-pointer">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 mb-2">Nama Tambahan</label>
                    <input type="text" name="name" id="edit_addon_name" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 mb-2">Harga (Rp)</label>
                        <input type="number" name="price" id="edit_addon_price" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 mb-2">Ekstra (Mnt)</label>
                        <input type="number" name="extra_minutes" id="edit_addon_extra" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 outline-none transition">
                    </div>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white hover:bg-brand-primary text-sm font-extrabold rounded-xl transition shadow-lg flex items-center gap-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editCategory(id, name) {
        document.getElementById('edit_category_name').value = name;
        document.getElementById('form-edit-category').action = `/admin/categories/${id}`;
        document.getElementById('modal-edit-category').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('modal-edit-category').classList.add('hidden');
    }

    function editAddon(id, name, price, extra, categoryId) {
        document.getElementById('edit_addon_name').value = name;
        document.getElementById('edit_addon_price').value = price;
        document.getElementById('edit_addon_extra').value = extra;
        document.getElementById('edit_addon_category').value = categoryId;
        document.getElementById('form-edit-addon').action = `/admin/addons/${id}`;
        document.getElementById('modal-edit-addon').classList.remove('hidden');
    }

    function closeAddonModal() {
        document.getElementById('modal-edit-addon').classList.add('hidden');
    }
</script>
@endpush
@endsection
