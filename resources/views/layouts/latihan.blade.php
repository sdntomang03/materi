<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Mode Ujian - UjianPro')</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CSS CDN + Typography Plugin -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>

    <!-- KaTeX CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        /* Anti-Scroll KaTeX Fix */
        .katex-display {
            margin: 0 !important;
            padding: 0 !important;
            display: block;
            overflow: hidden;
        }

        .katex {
            font-size: 1.1em !important;
            color: inherit;
            white-space: nowrap;
        }

        /* Mencegah teks diseleksi/dicopy saat ujian (Opsional, hapus jika tidak perlu) */
        .no-select {
            -webkit-user-select: none;
            /* Safari */
            -ms-user-select: none;
            /* IE 10 and IE 11 */
            user-select: none;
            /* Standard syntax */
        }
    </style>
</head>

<!-- Tambahkan class no-select di body agar soal tidak bisa di-copy paste -->

<body class="antialiased text-slate-800 bg-slate-100 min-h-screen flex flex-col no-select">

    <!-- NAVBAR KHUSUS UJIAN (Tanpa Menu Navigasi) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 sm:h-16 flex items-center justify-between">

            <!-- Logo Saja (Tidak bisa diklik agar tidak sengaja keluar ujian) -->
            <div class="font-black text-lg sm:text-xl tracking-tight text-slate-800 flex items-center gap-2">
                <div
                    class="w-7 h-7 sm:w-8 sm:h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md transform rotate-3">
                    <i class="fas fa-rocket text-xs sm:text-sm"></i>
                </div>
                <span>Ujian<span class="text-indigo-600">Pro</span></span>
            </div>

            <!-- Indikator Mode Ujian -->
            <div class="flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span
                    class="text-xs sm:text-sm font-bold text-emerald-600 uppercase tracking-widest hidden sm:inline-block">
                    Mode Latihan Berlangsung
                </span>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest sm:hidden">
                    Mode Latihan
                </span>
            </div>

        </div>
    </header>

    <!-- BREADCRUMB (Jalur Navigasi Simple) -->
    <div class="bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3">
            <nav
                class="flex items-center text-xs sm:text-sm font-bold text-slate-500 overflow-x-auto no-scrollbar whitespace-nowrap">
                @yield('breadcrumb')
            </nav>
        </div>
    </div>

    <!-- KONTEN UTAMA UJIAN SPA -->
    <main class="flex-1 w-full max-w-7xl mx-auto py-0 sm:py-8 px-0 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- FOOTER MINIMALIS -->
    <footer class="bg-slate-100 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-400 font-bold text-xs sm:text-sm">
                Sistem Ujian Terpadu &copy; {{ date('Y') }}
            </p>
        </div>
    </footer>

    <!-- JAVASCRIPT GLOBAL (Hanya KaTeX, tanpa logika menu) -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Render KaTeX untuk merender soal matematika
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false}
                ],
                throwOnError : false
            });

            // (Opsional) Mencegah klik kanan agar siswa tidak menginspeksi elemen/mencotek
            document.addEventListener('contextmenu', event => event.preventDefault());
        });
    </script>
</body>

</html>