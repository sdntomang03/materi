<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'UjianPro - Platform Belajar')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .bg-ornament {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col">

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

    <!-- KONTEN UTAMA -->
    <main class="flex-1 w-full max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- FOOTER -->
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

    <!-- KATEX & SCRIPTS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError : false
            });
        });
    </script>
</body>

</html>