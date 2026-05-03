@extends('layouts.main')

@section('title', 'Try Out ' . ($subject->name ?? '') . ' - UjianPro')

@section('content')
<div class="relative pt-12 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Tombol Kembali -->
        <a href="{{ route('subject.index', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug]) }}"
            class="inline-flex items-center text-slate-500 hover:text-rose-600 font-bold text-sm mb-8 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Mapel
        </a>

        <!-- Header Section -->
        <div class="mb-10 flex items-center gap-4">
            <div
                class="w-16 h-16 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm shrink-0">
                <i class="fas fa-stopwatch"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $level->name }} &bull;
                    {{ $grade->name }}</div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Try Out {{ $subject->name }}</h1>
            </div>
        </div>

        <!-- Daftar Ujian (Exam) -->
        <div class="space-y-6">
            @forelse($exams as $exam)
            <div
                class="bg-white border-2 border-slate-200 rounded-[2rem] p-6 sm:p-8 hover:border-rose-300 hover:shadow-xl transition-all group">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 mb-2">{{ $exam->title }}</h2>
                        <p class="text-slate-500 leading-relaxed">{{ $exam->description }}</p>
                    </div>
                    <div class="bg-rose-50 text-rose-600 p-3 rounded-2xl hidden sm:block">
                        <i class="fas fa-file-signature text-2xl"></i>
                    </div>
                </div>

                <!-- Area Link Eksternal JSON -->
                @if($exam->links)
                @php $links = json_decode($exam->links, true); @endphp
                @if(is_array($links) && count($links) > 0)
                <div class="mt-8 flex flex-col sm:flex-row gap-4 border-t border-slate-100 pt-6">
                    @foreach($links as $link)
                    <a href="{{ $link['url'] }}" target="_blank"
                        class="flex-1 text-center bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-sm shadow-rose-200 flex items-center justify-center gap-2">
                        <i class="fas fa-external-link-alt text-rose-200"></i> {{ $link['title'] }}
                    </a>
                    @endforeach
                </div>
                @endif
                @endif
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-[2rem] border border-slate-200 shadow-sm">
                <div class="text-slate-300 text-6xl mb-4"><i class="fas fa-clipboard-list"></i></div>
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Try Out</h3>
                <p class="text-slate-500">Jadwal ujian atau try out untuk pelajaran ini belum tersedia.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection