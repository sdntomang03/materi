@extends('layouts.app')
<!-- Sesuaikan jika Anda punya layout khusus admin -->

@section('title', 'Manajemen Kurikulum - UjianPro')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-slate-800">Manajemen Kurikulum</h1>
                <p class="text-slate-500 mt-1">Pilih jenjang pendidikan untuk mengelola kelas dan materi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($levels as $level)
            <a href="{{ route('admin.courses.level', $level->id) }}"
                class="bg-white rounded-[2rem] p-8 border border-slate-200 hover:border-indigo-400 hover:shadow-xl transition-all group flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 mb-2">{{ $level->name }}</h2>
                <span class="bg-slate-100 text-slate-600 font-bold px-4 py-1 rounded-full text-sm">
                    {{ $level->grades_count }} Kelas
                </span>
            </a>
            @endforeach
        </div>

    </div>
</div>
@endsection