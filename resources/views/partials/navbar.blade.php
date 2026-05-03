<header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <div class="flex items-center gap-8">
            <!-- Logo -->
            <a href="{{ url('/') }}"
                class="font-black text-xl tracking-tight text-slate-800 flex items-center gap-2 hover:opacity-80 transition-opacity">
                <div
                    class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md transform rotate-3">
                    <i class="fas fa-rocket text-sm"></i>
                </div>
                <span>Ujian<span class="text-indigo-600">Pro</span></span>
            </a>

            <!-- Menu Navigasi (Desktop - Disembunyikan di Mobile) -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-bold">
                <a href="{{ url('/') }}"
                    class="{{ request()->is('/') ? 'text-indigo-600 border-b-2 border-indigo-600 py-5' : 'text-slate-500 hover:text-indigo-600 py-5' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ url('/materi') }}"
                    class="{{ request()->is('materi*') ? 'text-indigo-600 border-b-2 border-indigo-600 py-5' : 'text-slate-500 hover:text-indigo-600 py-5' }} transition-colors">
                    Materi
                </a>
                <a href="{{ url('/ujian') }}"
                    class="{{ request()->is('ujian*') ? 'text-indigo-600 border-b-2 border-indigo-600 py-5' : 'text-slate-500 hover:text-indigo-600 py-5' }} transition-colors">
                    CBT Ujian
                </a>
            </nav>
        </div>

        <!-- Tombol Hamburger (Mobile - Disembunyikan di Desktop) -->
        <div class="flex items-center md:hidden">
            <button type="button" id="mobile-menu-btn"
                class="text-slate-500 hover:text-indigo-600 focus:outline-none p-2 transition-colors">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

    </div>

    <!-- Panel Menu Navigasi (Mobile Dropdown) -->
    <div id="mobile-menu-panel" class="hidden md:hidden absolute w-full bg-white border-b border-slate-200 shadow-lg">
        <nav class="flex flex-col px-4 pt-2 pb-6 space-y-2 text-sm font-bold">
            <a href="{{ url('/') }}"
                class="{{ request()->is('/') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }} block px-4 py-3 rounded-xl transition-colors">
                <i class="fas fa-home w-6 text-center mr-1"></i> Beranda
            </a>
            <a href="{{ url('/materi') }}"
                class="{{ request()->is('materi*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }} block px-4 py-3 rounded-xl transition-colors">
                <i class="fas fa-book w-6 text-center mr-1"></i> Materi
            </a>
            <a href="{{ url('/ujian') }}"
                class="{{ request()->is('ujian*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }} block px-4 py-3 rounded-xl transition-colors">
                <i class="fas fa-laptop-code w-6 text-center mr-1"></i> CBT Ujian
            </a>
        </nav>
    </div>
</header>