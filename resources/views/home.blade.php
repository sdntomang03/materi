<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UjianPro - Platform Belajar Interaktif</title>

    <!-- Font & Icon -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Vite Tailwind (v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .bg-grid {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen relative overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="absolute w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg transform rotate-3">
                        <i class="fas fa-rocket text-lg"></i>
                    </div>
                    <span class="font-black text-2xl tracking-tight text-slate-800">
                        Ujian<span class="text-indigo-600">Pro</span>
                    </span>
                </div>

                <!-- Auth / Login -->
                <div class="flex items-center gap-4 font-bold">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-indigo-600">Dashboard Admin</a>
                    @else
                    <a href="{{ route('login') }}"
                        class="text-slate-600 hover:text-indigo-600 hidden sm:block">Masuk</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md transition-all hover:-translate-y-0.5">Daftar
                        Akun</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO & JENJANG SECTION -->
    <div class="relative pt-32 pb-20 bg-grid flex-1 flex flex-col justify-center">
        <!-- Efek Cahaya Latar Belakang -->
        <div
            class="absolute top-20 left-1/2 -translate-x-1/2 w-full max-w-2xl h-full opacity-40 pointer-events-none -z-10">
            <div
                class="absolute top-0 -left-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50">
            </div>
            <div
                class="absolute top-0 -right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">

            <h1 class="text-5xl md:text-6xl font-black text-slate-900 tracking-tight mb-6">
                Belajar Lebih <span class="text-indigo-600">Seru,</span><br>
                Nilai Makin <span class="text-emerald-500">Maju!</span>
            </h1>

            <p class="mt-4 max-w-2xl text-lg text-slate-500 mx-auto font-medium mb-16">
                Silakan pilih jenjang pendidikanmu di bawah ini untuk mulai belajar, membaca materi, dan mengikuti Try
                Out secara interaktif.
            </p>

            <!-- TOMBOL LINK SD, SMP, SMA -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">

                <!-- KARTU SD -->
                <a href="{{ route('level.grades', 'sd') }}"
                    class="block bg-white rounded-[2rem] p-8 border border-slate-200 hover:border-emerald-400 hover:shadow-2xl hover:shadow-emerald-100/50 transition-all duration-300 transform hover:-translate-y-2 group text-left">
                    <div
                        class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 text-3xl mb-6 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mb-2">Sekolah Dasar</h3>
                    <p class="text-slate-500 font-medium mb-6">Materi visual, interaktif, dan latihan dasar (Kelas 1 -
                        6).</p>
                    <div class="flex items-center text-emerald-500 font-bold text-sm">
                        Mulai Belajar SD <i
                            class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </a>

                <!-- KARTU SMP -->
                <a href="{{ route('level.grades', 'smp') }}"
                    class="block bg-white rounded-[2rem] p-8 border border-slate-200 hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-300 transform hover:-translate-y-2 group text-left relative">
                    <div
                        class="absolute top-0 right-8 bg-blue-500 text-white text-xs font-black px-3 py-1 rounded-b-lg">
                        POPULER</div>
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 text-3xl mb-6 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mb-2">SMP / MTs</h3>
                    <p class="text-slate-500 font-medium mb-6">Pemantapan konsep dan latihan soal ujian tingkat
                        menengah.</p>
                    <div class="flex items-center text-blue-500 font-bold text-sm">
                        Mulai Belajar SMP <i
                            class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </a>

                <!-- KARTU SMA -->
                <a href="{{ route('level.grades', 'sma') }}"
                    class="block bg-white rounded-[2rem] p-8 border border-slate-200 hover:border-rose-400 hover:shadow-2xl hover:shadow-rose-100/50 transition-all duration-300 transform hover:-translate-y-2 group text-left">
                    <div
                        class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 text-3xl mb-6 group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mb-2">SMA / SMK</h3>
                    <p class="text-slate-500 font-medium mb-6">Kupas tuntas soal HOTS dan persiapan UTBK / SNBT.</p>
                    <div class="flex items-center text-rose-500 font-bold text-sm">
                        Mulai Belajar SMA <i
                            class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 mt-auto relative z-10">
        <div class="max-w-7xl mx-auto py-6 px-4 text-center">
            <p class="text-slate-400 font-bold text-sm">
                &copy; {{ date('Y') }} UjianPro. Dibuat dengan cinta untuk pendidikan Indonesia.
            </p>
        </div>
    </footer>

</body>

</html>