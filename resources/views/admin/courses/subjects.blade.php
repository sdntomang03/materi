@extends('layouts.app')

@section('title', 'Mata Pelajaran ' . $grade->name . ' - ' . $menu->title)

@section('content')
<!-- BUNGKUS DENGAN x-data UNTUK ALPINE.JS MODAL -->
<div x-data="{
        openSubjectModal: false,
        editMode: false,
        formAction: '',
        subjectName: '',
        subjectIcon: 'fas fa-book',

        openModal(action, isEdit, name, icon) {
            this.formAction = action;
            this.editMode = isEdit;
            this.subjectName = name;
            this.subjectIcon = icon || 'fas fa-book';
            this.openSubjectModal = true;
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

        <!-- Tombol Kembali ke Pilihan Menu -->
        <a href="{{ route('admin.courses.grade.menus', $grade->id) }}"
            class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors group mb-8">
            <div
                class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
            </div>
            Kembali ke Menu {{ $grade->name }}
        </a>

        <!-- Header Section -->
        <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <div
                    class="inline-flex items-center gap-2 text-indigo-600 font-black uppercase tracking-widest text-xs mb-2 bg-indigo-100 px-3 py-1 rounded-full">
                    <i class="{{ $menu->icon }}"></i> {{ $menu->title }} &bull; {{ $grade->name }}
                </div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pilih Mata Pelajaran</h1>
                <p class="text-slate-500 mt-1">Kelola bab dan konten untuk menu {{ $menu->title }}.</p>
            </div>

            <!-- Tombol Tambah Mapel (Buka Modal) -->
            <button
                @click="openModal('{{ route('admin.courses.subject.store', $grade->id) }}', false, '', 'fas fa-book')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-sm hover:shadow-lg transition-all flex items-center justify-center gap-2 shrink-0">
                <i class="fas fa-plus"></i> Tambah Mapel
            </button>
        </div>

        <!-- Grid Mata Pelajaran -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($subjects as $subject)

            <!-- KARTU MAPEL (Diubah jadi div agar tombol Edit/Hapus bisa masuk) -->
            <div
                class="bg-white rounded-[2rem] p-6 border border-slate-200 hover:border-indigo-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col relative overflow-hidden">

                <!-- Header Kartu: Icon & Tombol Aksi -->
                <div class="flex justify-between items-start mb-4">
                    <div
                        class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                    </div>

                    <!-- Tombol Aksi (Edit & Hapus) -->
                    <div class="flex gap-2 relative z-20">
                        <button type="button"
                            @click="openModal('{{ route('admin.courses.subject.update', $subject->id) }}', true, '{{ addslashes($subject->name) }}', '{{ addslashes($subject->icon) }}')"
                            class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-blue-100 hover:text-blue-600 flex items-center justify-center transition-colors">
                            <i class="fas fa-edit text-xs"></i>
                        </button>

                        <form action="{{ route('admin.courses.subject.destroy', $subject->id) }}" method="POST"
                            class="m-0 inline-block"
                            onsubmit="return confirm('Peringatan: Menghapus Mapel akan menghapus SEMUA Bab dan Materi di dalamnya. Lanjutkan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-red-100 hover:text-red-600 flex items-center justify-center transition-colors">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Area Link Utama (Masuk ke Detail) -->
                <a href="{{ route('admin.courses.subject.detail', ['grade_id' => $grade->id, 'menu_id' => $menu->id, 'subject_id' => $subject->id]) }}"
                    class="flex-1 block relative z-10">
                    <h3 class="text-xl font-black text-slate-800 group-hover:text-indigo-700 transition-colors">
                        {{ $subject->name }}
                    </h3>
                    <div class="flex items-center justify-between mt-4 border-t border-slate-100 pt-4">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">
                            Kelola Bab
                        </span>
                        <div
                            class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                            <i
                                class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

            </div>

            @empty
            <!-- Empty State -->
            <div
                class="col-span-full py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                <div class="text-slate-300 text-6xl mb-4"><i class="fas fa-book-open"></i></div>
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Mapel</h3>
                <p class="text-slate-500">Silakan tambah mata pelajaran untuk menu ini terlebih dahulu.</p>
                <button
                    @click="openModal('{{ route('admin.courses.subject.store', $grade->id) }}', false, '', 'fas fa-book')"
                    class="mt-6 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors">
                    <i class="fas fa-plus mr-2"></i> Tambah Mapel
                </button>
            </div>
            @endforelse
        </div>

    </div>

    <!-- ============================================== -->
    <!-- MODAL FORM MAPEL (CREATE / EDIT)               -->
    <!-- ============================================== -->
    <div x-show="openSubjectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div x-show="openSubjectModal" x-transition.opacity
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal Panel -->
            <div x-show="openSubjectModal" @click.away="openSubjectModal = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200">

                <div class="bg-white px-6 pb-4 pt-6 sm:p-8 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800"
                            x-text="editMode ? 'Edit Mata Pelajaran' : 'Tambah Mapel Baru'"></h3>
                        <button @click="openSubjectModal = false"
                            class="text-slate-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- FORM SUBMIT -->
                    <form :action="formAction" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">

                        <div class="space-y-5">
                            <!-- Nama Mapel -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Nama Mata
                                    Pelajaran</label>
                                <input type="text" name="name" id="name" x-model="subjectName" required
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                    placeholder="Contoh: Matematika">
                            </div>

                            <!-- Icon Mapel dengan Preview -->
                            <div>
                                <label for="icon" class="block text-sm font-bold text-slate-700 mb-1">Icon
                                    (FontAwesome)</label>
                                <div class="flex gap-4 items-center">
                                    <!-- Kotak Preview Icon -->
                                    <div
                                        class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shrink-0 border border-indigo-100 shadow-inner">
                                        <i :class="subjectIcon || 'fas fa-book'"></i>
                                    </div>
                                    <!-- Input Icon -->
                                    <div class="flex-1">
                                        <input type="text" name="icon" id="icon" x-model="subjectIcon"
                                            class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-slate-50"
                                            placeholder="Contoh: fas fa-calculator">
                                        <p class="text-xs text-slate-500 mt-1">Cari referensi icon di <a
                                                href="https://fontawesome.com/icons" target="_blank"
                                                class="text-indigo-500 hover:underline">fontawesome.com</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <button type="button" @click="openSubjectModal = false"
                                class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                                <i class="fas fa-save"></i> <span
                                    x-text="editMode ? 'Simpan Perubahan' : 'Simpan Mapel'"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection