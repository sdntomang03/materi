@extends('layouts.main')

@section('title', 'Pilih Mapel - UjianPro')

@section('content')
<div class="relative pt-12 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Tombol Kembali -->
        <a href="{{ route('grade.menu', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug]) }}"
            class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm mb-8 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Menu {{ $grade->name }}
        </a>

        <!-- Header Section -->
        <div class="text-center mb-16">
            <div
                class="inline-block bg-indigo-100 text-indigo-700 font-black px-4 py-1.5 rounded-full text-sm uppercase tracking-widest mb-4">
                {{ $menu->title }} &bull; {{ $grade->name }}
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Mata <span class="text-indigo-600">Pelajaran</span>
            </h1>
            <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto">
                Pilih mata pelajaran yang ingin kamu akses.
            </p>
        </div>

        <!-- Grid Daftar Mapel -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
            @forelse($subjects as $subject)
            <!-- Kartu Mapel -->
            <a href="{{ route('subject.show', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug, 'subject_slug' => $subject->slug]) }}"
                class="bg-white border border-slate-200 rounded-3xl p-6 flex items-center gap-5 hover:border-indigo-400 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300 transform hover:-translate-y-1 group relative overflow-hidden">

                <div
                    class="w-14 h-14 shrink-0 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors shadow-sm">
                    <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                </div>

                <div>
                    <h3
                        class="font-bold text-lg text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight">
                        {{ $subject->name }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-widest">
                        {{ $grade->name }}
                    </p>
                </div>

                <!-- Ikon Panah Kanan -->
                <div
                    class="ml-auto text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-16 bg-white rounded-[2rem] border border-slate-200 shadow-sm">
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Mapel</h3>
                <p class="text-slate-500">Mata pelajaran untuk kelas ini belum ditambahkan.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection