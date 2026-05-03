@extends('layouts.app')

@section('title', 'Pilih Menu - ' . $grade->name)

@section('content')
<!-- BUNGKUS DENGAN x-data UNTUK ALPINE.JS -->
<div x-data="{
        openMenuModal: false,
        editMode: false,
        formAction: '',
        menuTitle: '',
        menuDescription: '',
        menuIcon: 'fas fa-star',
        menuColor: 'blue',
        menuOrder: 0,

        openModal(action, isEdit, title, description, icon, color, order) {
            this.formAction = action;
            this.editMode = isEdit;
            this.menuTitle = title;
            this.menuDescription = description;
            this.menuIcon = icon || 'fas fa-star';
            this.menuColor = color || 'blue';
            this.menuOrder = order || 0;
            this.openMenuModal = true;
        }
    }" class="py-12 bg-slate-50 min-h-screen flex-1">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div
            class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()"><i class="fas fa-times text-emerald-500"></i></button>
        </div>
        @endif

        <!-- Error Validasi -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Navigasi Kembali -->
        <div class="mb-8">
            <a href="{{ route('admin.courses.level', $grade->education_level_id) }}"
                class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors group">
                <div
                    class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                </div>
                Kembali ke Daftar Kelas
            </a>
        </div>

        <!-- Header Halaman -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">
                    Menu Kelola <span class="text-indigo-600">{{ $grade->name }}</span>
                </h1>
                <p class="text-slate-500 mt-1">Pilih kategori konten yang ingin Anda kelola untuk kelas ini.</p>
            </div>

            <!-- Tombol Tambah Menu (Perhatikan Pengiriman education_level_id) -->
            <button
                @click="openModal('{{ route('admin.courses.menu.store', $grade->education_level_id) }}', false, '', '', 'fas fa-star', 'blue', 0)"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Menu
            </button>
        </div>

        <!-- Grid Menu -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($menus as $menu)
            <!-- KARTU MENU -->
            <div
                class="relative bg-white group overflow-hidden rounded-[2.5rem] border border-slate-200 hover:border-indigo-500 hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-300 flex flex-col">

                <!-- Tombol Aksi (Edit & Hapus) - Melayang di Pojok Kanan Atas -->
                <div
                    class="absolute top-4 right-4 flex gap-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button type="button"
                        @click="openModal('{{ route('admin.courses.menu.update', $menu->id) }}', true, @json($menu->title), @json($menu->description), @json($menu->icon), @json($menu->color_theme), {{ $menu->order_num }})"
                        class="w-8 h-8 rounded-full bg-white/80 backdrop-blur border border-slate-200 text-slate-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 flex items-center justify-center shadow-sm transition-all">
                        <i class="fas fa-edit text-xs"></i>
                    </button>

                    <form action="{{ route('admin.courses.menu.destroy', $menu->id) }}" method="POST" class="m-0"
                        onsubmit="return confirm('Peringatan: Menghapus Menu akan menghapus semua Mata Pelajaran dan Bab di dalamnya! Yakin?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-8 h-8 rounded-full bg-white/80 backdrop-blur border border-slate-200 text-slate-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 flex items-center justify-center shadow-sm transition-all">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>

                <!-- Area Klik Utama -->
                <a href="{{ route('admin.courses.subjects', [$grade->id, $menu->id]) }}"
                    class="p-8 flex flex-col items-center text-center h-full z-10">

                    <!-- Icon Wrapper (Warna bisa disesuaikan dengan Tailwind safelist jika mau) -->
                    <div
                        class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-indigo-600 group-hover:text-white group-hover:rotate-6 transition-all duration-500">
                        <i class="{{ $menu->icon ?? 'fas fa-star' }}"></i>
                    </div>

                    <!-- Text Content -->
                    <h2 class="text-2xl font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                        {{ $menu->title }}
                    </h2>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">
                        {{ $menu->description }}
                    </p>

                    <!-- Action Label -->
                    <div
                        class="mt-auto inline-flex items-center text-indigo-600 font-bold text-xs uppercase tracking-widest">
                        Buka Manajemen <i
                            class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            </div>
            @empty
            <!-- Empty State -->
            <div
                class="col-span-full mt-4 p-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                <div class="text-slate-300 text-6xl mb-4">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Menu</h3>
                <p class="text-slate-500 mb-6">Data menu untuk jenjang ini belum tersedia di database.</p>
                <button
                    @click="openModal('{{ route('admin.courses.menu.store', $grade->education_level_id) }}', false, '', '', 'fas fa-star', 'blue', 0)"
                    class="bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white px-6 py-3 rounded-2xl font-bold text-sm transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i> Buat Menu Pertama
                </button>
            </div>
            @endforelse
        </div>

    </div>

    <!-- ============================================== -->
    <!-- MODAL FORM MENU (CREATE / EDIT)                -->
    <!-- ============================================== -->
    <div x-show="openMenuModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="openMenuModal" x-transition.opacity
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="openMenuModal" @click.away="openMenuModal = false" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">

                <div class="bg-white px-6 pb-4 pt-6 sm:p-8 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800"
                            x-text="editMode ? 'Edit Menu' : 'Tambah Menu Baru'"></h3>
                        <button @click="openMenuModal = false"
                            class="text-slate-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- FORM SUBMIT -->
                    <form :action="formAction" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">

                        <div class="space-y-5">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Judul Menu -->
                                <div>
                                    <label for="title" class="block text-sm font-bold text-slate-700 mb-1">Judul
                                        Menu</label>
                                    <input type="text" name="title" id="title" x-model="menuTitle" required
                                        class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                        placeholder="Contoh: Materi Pelajaran">
                                </div>

                                <!-- Urutan Tampil (Order Num) -->
                                <div>
                                    <label for="order_num" class="block text-sm font-bold text-slate-700 mb-1">Urutan
                                        Tampil (Order)</label>
                                    <input type="number" name="order_num" id="order_num" x-model="menuOrder" min="0"
                                        required
                                        class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                        placeholder="Contoh: 1">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-slate-700 mb-1">Deskripsi
                                    Singkat</label>
                                <textarea name="description" id="description" rows="3" x-model="menuDescription"
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                    placeholder="Contoh: Kumpulan materi bacaan dan rangkuman."></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Tema Warna (Dropdown) -->
                                <div>
                                    <label for="color_theme" class="block text-sm font-bold text-slate-700 mb-1">Tema
                                        Warna</label>
                                    <select name="color_theme" id="color_theme" x-model="menuColor"
                                        class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50 cursor-pointer">
                                        <option value="blue">Blue</option>
                                        <option value="indigo">Indigo</option>
                                        <option value="emerald">Emerald</option>
                                        <option value="rose">Rose</option>
                                        <option value="purple">Purple</option>
                                        <option value="amber">Amber</option>
                                    </select>
                                </div>

                                <!-- Icon Menu dengan Preview -->
                                <div>
                                    <label for="icon" class="block text-sm font-bold text-slate-700 mb-1">Icon
                                        (FontAwesome)</label>
                                    <div class="flex gap-3 items-center">
                                        <!-- Preview Icon dipengaruhi Tema Warna (Hanya di modal saja) -->
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-inner"
                                            :class="{
                                                'bg-blue-50 text-blue-600 border-blue-100': menuColor === 'blue',
                                                'bg-indigo-50 text-indigo-600 border-indigo-100': menuColor === 'indigo',
                                                'bg-emerald-50 text-emerald-600 border-emerald-100': menuColor === 'emerald',
                                                'bg-rose-50 text-rose-600 border-rose-100': menuColor === 'rose',
                                                'bg-purple-50 text-purple-600 border-purple-100': menuColor === 'purple',
                                                'bg-amber-50 text-amber-600 border-amber-100': menuColor === 'amber'
                                             }">
                                            <i :class="menuIcon || 'fas fa-star'"></i>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" name="icon" id="icon" x-model="menuIcon"
                                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                                placeholder="Contoh: fas fa-book">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <button type="button" @click="openMenuModal = false"
                                class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                                <i class="fas fa-save"></i> <span
                                    x-text="editMode ? 'Simpan Perubahan' : 'Simpan Menu'"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection