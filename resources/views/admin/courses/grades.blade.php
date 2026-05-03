@extends('layouts.app')

@section('title', 'Kelola Kelas ' . $level->name)

@section('content')
<div class="py-12 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <a href="{{ route('admin.courses.index') }}"
            class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Jenjang
        </a>

        <div class="mb-8 flex justify-between items-end">
            <div>
                <div class="text-indigo-600 font-black uppercase tracking-widest text-sm mb-1">Jenjang {{ $level->name
                    }}</div>
                <h1 class="text-3xl font-black text-slate-800">Daftar Kelas</h1>
            </div>
            <!-- Tombol Tambah (Disiapkan untuk fitur CRUD nanti) -->
            <button
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($grades as $grade)
            <a href="{{ route('admin.courses.grade.menus', $grade->id) }}"
                class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-indigo-400 hover:shadow-lg transition-all group text-center">
                <h3 class="text-xl font-black text-slate-800 group-hover:text-indigo-600">
                    {{ $grade->name }}
                </h3>
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-slate-500">Belum ada data kelas.</div>
            @endforelse
        </div>

    </div>
</div>
@endsection