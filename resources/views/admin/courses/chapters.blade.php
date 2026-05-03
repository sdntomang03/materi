@extends('layouts.app')

@section('title', 'Daftar Bab ' . $subject->name . ' - ' . $grade->name)

@section('content')
<!-- BUNGKUS DENGAN x-data UNTUK ALPINE.JS MODAL -->
<div x-data="{
        openChapterModal: false,
        editMode: false,
        formAction: '',
        chapterName: '',
        chapterOrder: '',

        openModal(action, isEdit, name, order) {
            this.formAction = action;
            this.editMode = isEdit;
            this.chapterName = name;
            this.chapterOrder = order;
            this.openChapterModal = true;
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
            <button onclick="this.parentElement.style.display='none'"><i
                    class="fas fa-times text-emerald-500"></i></button>
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

        <!-- Tombol Kembali ke Daftar Mapel -->
        <a href="{{ route('admin.courses.subjects', ['grade_id' => $grade->id, 'menu_id' => $menu->id]) }}"
            class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors group mb-8">
            <div
                class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
            </div>
            Kembali ke Daftar Mapel
        </a>

        <!-- Header Section -->
        <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <!-- Breadcrumbs/Badge -->
                <div
                    class="inline-flex items-center gap-2 text-indigo-600 font-black uppercase tracking-widest text-xs mb-3 bg-indigo-100 px-4 py-1.5 rounded-full">
                    <i class="{{ $menu->icon }}"></i> {{ $menu->title }} &bull; {{ $grade->name }}
                </div>
                <!-- Judul Utama -->
                <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="{{ $subject->icon ?? 'fas fa-book' }} text-indigo-500"></i> {{ $subject->name }}
                </h1>
                <p class="text-slate-500 mt-2 font-medium">Kelola susunan bab dan isi konten untuk mata pelajaran ini.
                </p>
            </div>

            <!-- Tombol Tambah Bab (Buka Modal) -->
            <button @click="openModal('{{ route('admin.courses.chapter.store', $subject->id) }}', false, '', '')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Bab Baru
            </button>
        </div>

        <!-- Grid Daftar Bab -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($chapters as $chapter)

            <!-- KARTU BAB -->
            <div
                class="bg-white rounded-[2rem] p-6 border border-slate-200 hover:border-indigo-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col relative overflow-hidden">

                <!-- Hiasan Nomor Bab di Background -->
                <div
                    class="absolute -right-4 -top-6 text-[8rem] font-black text-slate-50 group-hover:text-indigo-50/50 transition-colors z-0 select-none">
                    {{ $chapter->order_num }}
                </div>

                <!-- Area Klik untuk Masuk ke Konten -->
                <a href="{{ route('admin.courses.chapter', ['menu_id' => $menu->id, 'chapter_id' => $chapter->id]) }}"
                    class="relative z-10 flex-1 block">
                    <div class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-2">
                        Bab {{ $chapter->order_num }}
                    </div>
                    <h3 class="text-xl font-black text-slate-800 group-hover:text-indigo-700 transition-colors mb-6">
                        {{ $chapter->name }}
                    </h3>

                    <!-- Statistik -->
                    <div class="flex gap-4 mb-2">
                        <div
                            class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 group-hover:border-indigo-100 group-hover:bg-indigo-50 transition-colors">
                            <i class="fas fa-file-alt text-slate-400 group-hover:text-indigo-500"></i>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-indigo-700">
                                {{ $chapter->materials_count }} Materi
                            </span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 group-hover:border-emerald-100 group-hover:bg-emerald-50 transition-colors">
                            <i class="fas fa-tasks text-slate-400 group-hover:text-emerald-500"></i>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-700">
                                {{ $chapter->exercises_count }} Latihan
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Footer Card dengan Tombol Edit -->
                <div class="relative z-10 mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- Tombol Edit Bab -->
                        <button type="button"
                            @click="openModal('{{ route('admin.courses.chapter.update', $chapter->id) }}', true, '{{ addslashes($chapter->name) }}', '{{ $chapter->order_num }}')"
                            class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-100 hover:text-blue-600 transition-colors">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Aksi</span>
                    </div>

                    <!-- Tombol Masuk/Buka -->
                    <a href="{{ route('admin.courses.chapter', ['menu_id' => $menu->id, 'chapter_id' => $chapter->id]) }}"
                        class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 hover:bg-indigo-600 hover:text-white transition-all">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>

            @empty
            <!-- Empty State -->
            <div
                class="col-span-full py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                <div class="text-slate-300 text-6xl mb-4"><i class="fas fa-bookmark"></i></div>
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Bab</h3>
                <p class="text-slate-500">Silakan tambahkan bab pertama untuk mata pelajaran {{ $subject->name }}.</p>
                <button @click="openModal('{{ route('admin.courses.chapter.store', $subject->id) }}', false, '', '')"
                    class="mt-6 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors">
                    <i class="fas fa-plus mr-2"></i> Tambah Bab Sekarang
                </button>
            </div>
            @endforelse
        </div>

    </div>

    <!-- ============================================== -->
    <!-- MODAL FORM BAB (CREATE / EDIT)                 -->
    <!-- ============================================== -->
    <div x-show="openChapterModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div x-show="openChapterModal" x-transition.opacity
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal Panel -->
            <div x-show="openChapterModal" @click.away="openChapterModal = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200">

                <div class="bg-white px-6 pb-4 pt-6 sm:p-8 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800"
                            x-text="editMode ? 'Edit Bab' : 'Tambah Bab Baru'"></h3>
                        <button @click="openChapterModal = false"
                            class="text-slate-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- FORM -->
                    <form :action="formAction" method="POST">
                        @csrf
                        <!-- Input PUT aktif otomatis jika dalam mode Edit -->
                        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">

                        <div class="space-y-5">
                            <!-- Nomor Urut Bab -->
                            <div>
                                <label for="order_num" class="block text-sm font-bold text-slate-700 mb-1">Nomor
                                    Bab</label>
                                <input type="number" name="order_num" id="order_num" x-model="chapterOrder" required
                                    min="1"
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                    placeholder="Contoh: 1">
                            </div>

                            <!-- Judul Bab -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Nama Bab</label>
                                <input type="text" name="name" id="name" x-model="chapterName" required
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                    placeholder="Contoh: Pecahan Dasar">
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="openChapterModal = false"
                                class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                                <i class="fas fa-save"></i> <span
                                    x-text="editMode ? 'Simpan Perubahan' : 'Tambahkan Bab'"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection