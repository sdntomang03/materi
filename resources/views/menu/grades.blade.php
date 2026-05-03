@extends('layouts.main')

@section('title', 'Pilih Kelas ' . ($level->name ?? '') . ' - UjianPro')

@section('content')
<div class="relative pt-12 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Tombol Kembali -->
        <a href="{{ route('home') }}"
            class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm mb-8 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>

        <!-- Header Section -->
        <div class="text-center mb-16">
            <div
                class="inline-block bg-indigo-100 text-indigo-700 font-black px-4 py-1.5 rounded-full text-sm uppercase tracking-widest mb-4">
                Jenjang {{ $level->name }}
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Pilih <span class="text-indigo-600">Kelasmu</span>
            </h1>
            <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto">
                Klik kelasmu saat ini untuk melihat daftar menu, materi pelajaran, dan latihan soal yang tersedia.
            </p>
        </div>

        <!-- Grid Daftar Kelas -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            @forelse($grades as $grade)
            <!-- Kartu Kelas -->
            <a href="{{ route('grade.menu', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug]) }}"
                class="bg-white border border-slate-200 rounded-[2rem] p-6 sm:p-8 flex flex-col items-center justify-center hover:border-indigo-400 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300 transform hover:-translate-y-2 group text-center relative overflow-hidden">

                <!-- Hiasan Background (Muncul saat dihover) -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-blue-50 opacity-0 group-hover:opacity-100 transition-opacity -z-10">
                </div>

                <!-- Ikon Angka Kelas (Mengambil angka dari string "Kelas 4" menjadi "4") -->
                <div
                    class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center text-4xl font-black mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-sm group-hover:shadow-md transform group-hover:rotate-3 group-hover:scale-110 duration-300">
                    {{ preg_replace('/[^0-9]/', '', $grade->name) ?: substr($grade->name, 0, 1) }}
                </div>

                <h3 class="font-black text-xl text-slate-800 group-hover:text-indigo-700 transition-colors">
                    {{ $grade->name }}
                </h3>
            </a>
            @empty
            <!-- Tampilan jika kelas belum ada di database -->
            <div class="col-span-full text-center py-16 bg-white rounded-[2rem] border border-slate-200 shadow-sm">
                <div class="text-slate-300 text-6xl mb-4"><i class="fas fa-folder-open"></i></div>
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Kelas</h3>
                <p class="text-slate-500">Admin belum menambahkan data kelas untuk jenjang {{ $level->name }}.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection