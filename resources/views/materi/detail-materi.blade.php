@extends('layouts.materi')

@section('title', ($material->title ?? 'Materi') . ' - UjianPro')

@section('content')
<div class="relative pt-8 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Breadcrumb & Tombol Kembali -->
        <div class="mb-8">
            <a href="{{ route('subject.show', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug, 'subject_slug' => $subject->slug]) }}"
                class="inline-flex items-center text-slate-500 hover:text-blue-600 font-bold text-sm mb-4 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Bab
            </a>

            <div class="flex flex-wrap items-center text-xs font-bold text-slate-400 uppercase tracking-widest gap-2">
                <span>{{ $level->name }}</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span>{{ $grade->name }}</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-blue-500">{{ $subject->name }}</span>
            </div>
        </div>

        <!-- Judul Materi -->
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-8">
            {{ $material->title }}
        </h1>

        <!-- Konten Materi (Merender HTML dari Database) -->
        <div
            class="prose prose-slate prose-lg max-w-none prose-headings:font-black prose-a:text-blue-600 hover:prose-a:text-blue-500 prose-img:rounded-2xl">
            {!! $material->content !!}
        </div>

        <!-- Navigasi Bawah -->
        <div class="mt-16 pt-8 border-t border-slate-200 flex justify-center">
            <a href="{{ route('subject.show', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug, 'subject_slug' => $subject->slug]) }}"
                class="bg-white border-2 border-slate-200 text-slate-600 hover:border-blue-500 hover:text-blue-600 font-bold px-8 py-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                Selesai Membaca
            </a>
        </div>

    </div>
</div>
@endsection