@extends('layouts.materi')

@section('title', 'Menu ' . ($grade->name ?? '') . ' - UjianPro')

@section('content')
<div class="relative pt-12 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Tombol Kembali -->
        <a href="{{ route('level.grades', $level->slug) }}"
            class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm mb-8 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pilih Kelas
        </a>

        <!-- Header Section -->
        <div class="text-center mb-16">
            <div
                class="inline-block bg-indigo-100 text-indigo-700 font-black px-4 py-1.5 rounded-full text-sm uppercase tracking-widest mb-4">
                {{ $level->name }} &bull; {{ $grade->name }}
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Pilih <span class="text-indigo-600">Aktivitasmu</span>
            </h1>
            <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto">
                Mau ngapain hari ini? Silakan pilih menu belajar atau latihan di bawah ini.
            </p>
        </div>

        <!-- Grid Daftar Menu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
            @forelse($menus as $menu)
            <!-- Menentukan Warna Tema Berdasarkan Database -->
            @php
            $colorClass = 'blue';
            if($menu->color_theme == 'emerald') { $colorClass = 'emerald'; }
            elseif($menu->color_theme == 'rose') { $colorClass = 'rose'; }
            elseif($menu->color_theme == 'purple') { $colorClass = 'purple'; }
            @endphp

            <!-- Kartu Menu -->
            <a href="{{ route('subject.index', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug]) }}"
                class="bg-white border border-slate-200 rounded-[2rem] p-8 flex flex-col items-center hover:border-{{ $colorClass }}-400 hover:shadow-2xl hover:shadow-{{ $colorClass }}-100/50 transition-all duration-300 transform hover:-translate-y-2 group text-center relative overflow-hidden">

                <div
                    class="absolute inset-0 bg-gradient-to-br from-{{ $colorClass }}-50 to-white opacity-0 group-hover:opacity-100 transition-opacity -z-10">
                </div>

                <div
                    class="w-16 h-16 bg-{{ $colorClass }}-50 text-{{ $colorClass }}-500 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-{{ $colorClass }}-500 group-hover:text-white transition-colors shadow-sm group-hover:scale-110 duration-300">
                    <i class="{{ $menu->icon ?? 'fas fa-book' }}"></i>
                </div>

                <h3
                    class="font-black text-2xl text-slate-800 mb-2 group-hover:text-{{ $colorClass }}-700 transition-colors">
                    {{ $menu->title }}
                </h3>
                <p class="text-slate-500 text-sm">
                    {{ $menu->description }}
                </p>
            </a>
            @empty
            <div class="col-span-full text-center py-16 bg-white rounded-[2rem] border border-slate-200 shadow-sm">
                <h3 class="text-2xl font-black text-slate-700 mb-2">Belum Ada Menu</h3>
                <p class="text-slate-500">Menu untuk kelas ini belum tersedia.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection