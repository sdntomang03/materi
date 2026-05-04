@extends('layouts.app')

@section('title', 'Import Data Kurikulum')

@section('content')
<div class="max-w-4xl mx-auto py-12">

    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
            <i class="fas fa-file-import text-xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-800">Import Kurikulum JSON</h1>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div
        class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Kotak Upload -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200">
        <p class="text-slate-600 mb-6 font-medium">
            Unggah file <code class="bg-slate-100 px-2 py-1 rounded text-pink-600 text-sm">.json</code> yang berisi
            struktur mata pelajaran, bab, materi, dan soal. Sistem akan otomatis membangun kerangka kurikulum ke dalam
            database.
        </p>

        <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div
                class="border-2 border-dashed border-slate-300 rounded-2xl p-10 text-center hover:bg-slate-50 hover:border-indigo-400 transition-all group relative">
                <i
                    class="fas fa-cloud-upload-alt text-4xl text-slate-300 group-hover:text-indigo-500 mb-4 transition-colors"></i>
                <h4 class="text-lg font-bold text-slate-700 mb-1">Pilih atau Tarik File JSON ke Sini</h4>
                <p class="text-sm text-slate-400 mb-4">Maksimal ukuran file: 2MB</p>

                <!-- Input File disembunyikan dan di-overlay penuh di area putus-putus -->
                <input type="file" name="json_file" accept=".json" required
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                <span
                    class="inline-block bg-white border border-slate-200 shadow-sm text-slate-600 font-bold px-6 py-2 rounded-xl text-sm group-hover:border-indigo-300">Browse
                    File</span>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition-transform hover:-translate-y-1">
                    <i class="fas fa-magic mr-2"></i> Mulai Import Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection