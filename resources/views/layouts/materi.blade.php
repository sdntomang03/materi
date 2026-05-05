<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags Dinamis -->
    <title>@yield('title', 'UjianPro - Platform Belajar Interaktif')</title>

    <!-- Meta Description: Jika halaman tidak mengirimkan 'meta_description', gunakan teks default -->
    <meta name="description"
        content="@yield('meta_description', 'UjianPro adalah platform belajar interaktif dan ujian online untuk siswa SD, SMP, dan SMA. Tersedia materi lengkap dan soal HOTS terbaru.')">

    <!-- Meta Keywords: Jika halaman tidak mengirimkan 'meta_keywords', gunakan teks default -->
    <meta name="keywords"
        content="@yield('meta_keywords', 'ujian online, belajar interaktif, try out, bank soal, soal sdn tomang 03')">

    <meta name="author" content="Dian Nafi">

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

        /* Background Ornament */
        .bg-ornament {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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

        .bg-slate-950 .katex {
            color: white !important;
        }
    </style>
</head>

<body class="antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col">

    <!-- Memanggil Navbar Dinamis (Desktop & Mobile Panel) -->
    @include('partials.navbar')

    <!-- Breadcrumb Strip -->
    <div class="bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3">
            <nav
                class="flex items-center text-xs sm:text-sm font-bold text-slate-500 overflow-x-auto no-scrollbar whitespace-nowrap">

                <a href="{{ url('/') }}"
                    class="hover:text-indigo-600 transition-colors flex items-center gap-1.5 shrink-0">
                    <i class="fas fa-home text-sm sm:text-base"></i>
                    <span class="hidden sm:inline">Beranda</span>
                </a>

                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    @yield('breadcrumb')
                </div>

            </nav>
        </div>
    </div>

    <!-- Konten Utama Dinamis -->
    <main class="flex-1 w-full max-w-7xl mx-auto py-0 sm:py-8 px-0 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-10 border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-2 mb-4">
                <i class="fas fa-rocket text-indigo-600"></i>
                <span class="font-black tracking-tight text-slate-800">Ujian<span
                        class="text-indigo-600">Pro</span></span>
            </div>
            <p class="text-slate-400 font-bold text-sm">
                &copy; {{ date('Y') }} Platform Ujian & Belajar Interaktif.
            </p>
            <p class="text-slate-300 text-xs mt-1">Dikembangkan untuk SDN Tomang 03 Pagi</p>
        </div>
    </footer>
    <a href="https://chat.whatsapp.com/KQnupphQ9DmCIixaFZpNWk" target="_blank" rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-50 bg-green-500 text-white rounded-full flex items-center gap-2 px-4 py-2 shadow-lg hover:bg-green-600 hover:-translate-y-1 hover:shadow-green-500/50 transition-all duration-300"
        aria-label="Bergabung ke Grup WhatsApp">

        <!-- Ikon WhatsApp (Ukuran diperkecil menjadi text-lg) -->
        <i class="fab fa-whatsapp text-lg"></i>

        <!-- Teks -->
        <span class="font-bold text-sm tracking-wide">Bergabung</span>
    </a>
    <!-- JAVASCRIPT GLOBAL -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // 1. Inisialisasi KaTeX Auto-Render
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false}
                ],
                throwOnError : false
            });

            // 2. Logika Mobile Menu (Hamburger Toggle)
            const btn = document.getElementById('mobile-menu-btn');
            const panel = document.getElementById('mobile-menu-panel');

            if (btn && panel) {
                const icon = btn.querySelector('i');

                btn.addEventListener('click', function() {
                    panel.classList.toggle('hidden');

                    if (panel.classList.contains('hidden')) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    } else {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    }
                });
            }
        });
    </script>

</body>

</html>