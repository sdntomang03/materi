@extends('layouts.main')

@section('title', 'Daftar Latihan ' . ($subject->name ?? '') . ' - UjianPro')

@section('content')
<div class="relative pt-12 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Tombol Kembali -->
        <a href="{{ route('subject.index', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug]) }}"
            class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-bold text-sm mb-8 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Mapel
        </a>

        <!-- Header Section -->
        <div class="mb-10 flex items-center gap-4">
            <div
                class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm shrink-0">
                <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $level->name }} &bull;
                    {{ $grade->name }}</div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $subject->name }}</h1>
            </div>
        </div>

        <!-- Daftar Bab & Latihan -->
        <div class="space-y-6">
            @forelse($chapters as $chapter)
            <div class="bg-white border border-slate-200 rounded-[2rem] overflow-hidden shadow-sm">
                <!-- Judul Bab -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-black text-lg text-slate-700">{{ $chapter->name }}</h2>
                    <span
                        class="text-xs font-bold text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-200">
                        {{ $chapter->exercises->count() }} Latihan
                    </span>
                </div>

                <!-- List Latihan -->
                <div class="p-2">
                    @forelse($chapter->exercises as $exercise)
                    <a href="{{ route('exercise.show', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug, 'subject_slug' => $subject->slug, 'exercise_slug' => $exercise->slug]) }}"
                        class="flex items-center p-4 hover:bg-emerald-50 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors shrink-0">
                            <i class="fas fa-pencil-ruler"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="font-bold text-slate-700 group-hover:text-emerald-700 transition-colors">{{
                                $exercise->title }}</h3>
                            <p class="text-xs text-slate-400 mt-1"><i class="far fa-clock mr-1"></i> Waktu: {{
                                $exercise->duration_minutes }} Menit</p>
                        </div>
                        <div
                            class="text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all">
                            <span
                                class="text-sm font-bold mr-2 opacity-0 group-hover:opacity-100 transition-opacity">Kerjakan</span>
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    @empty
                    <div class="p-4 text-center text-sm text-slate-400 font-medium italic">
                        Belum ada latihan di bab ini.
                    </div>
                    @endforelse
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-[2rem] border border-slate-200 shadow-sm">
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Bab</h3>
                <p class="text-slate-500">Latihan soal untuk pelajaran ini sedang disiapkan.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection