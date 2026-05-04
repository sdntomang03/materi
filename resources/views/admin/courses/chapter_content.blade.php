@extends('layouts.app')

@section('title', 'Konten Bab: ' . $chapter->name)

@section('content')

<!-- CDN TinyMCE & MathJax (Untuk Materi) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    window.MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']],
            displayMath: [['$$', '$$'], ['\\[', '\\]']]
        },
        startup: { typeset: false }
    };
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<!-- CDN KaTeX (Untuk Preview Rumus) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>

<!-- BUNGKUS UTAMA ALPINE.JS -->
<div x-data="{
        // ==========================================
        // 1. STATE MATERI
        // ==========================================
        openMaterialModal: false,
        editMode: false,
        formAction: '',
        materialTitle: '',
        materialContent: '',
        activeTab: 'write',

        openModal(action, isEdit, title, content) {
            this.formAction = action;
            this.editMode = isEdit;
            this.materialTitle = title;
            this.materialContent = content;
            this.activeTab = 'write';
            this.openMaterialModal = true;

            setTimeout(() => {
                if(tinymce.get('materi_editor')) {
                    tinymce.get('materi_editor').setContent(content);
                } else {
                    tinymce.init({
                        selector: '#materi_editor',
                        plugins: 'code lists link table',
                        toolbar: 'undo redo | blocks | bold italic | bullist numlist | code',
                        menubar: false,
                        height: 300,
                        setup: function (editor) {
                            editor.on('change keyup', function () { editor.save(); });
                        }
                    }).then(() => { tinymce.get('materi_editor').setContent(content); });
                }
            }, 100);
        },

        previewContent() {
            if(tinymce.get('materi_editor')) { this.materialContent = tinymce.get('materi_editor').getContent(); }
            this.activeTab = 'preview';
            this.$nextTick(() => { if(window.MathJax) MathJax.typesetPromise(); });
        },

        // ==========================================
        // 2. STATE LATIHAN SOAL (VISUAL BUILDER)
        // ==========================================
        openExerciseModal: false,
        exerciseTitle: '',
        questionsList: [],

        isJsonMode: false,
        rawJsonText: '',
        openExercisePreview: false,

        showQuestionEditor: false,
        editQuestionIndex: -1,
        isUploading: false,
        qForm: {
            type: 'pg', text: '', image: '', options: [], answer: '', pairs: []
        },

        // Toggle Mode JSON
        switchToJsonMode() {
            this.rawJsonText = JSON.stringify(this.questionsList, null, 2);
            this.isJsonMode = true;
        },

        // Sinkronisasi Balik ke Mode Visual
        switchToVisualMode() {
            try {
                const parsed = JSON.parse(this.rawJsonText);
                this.questionsList = Array.isArray(parsed) ? parsed : [];
                this.isJsonMode = false;
            } catch (e) {
                alert('Format JSON tidak valid! Silakan periksa kembali tanda koma atau kurung Anda.');
            }
        },

        // Modal Preview Siswa
        renderPreviewMath() {
            if (this.isJsonMode) {
                try {
                    const parsed = JSON.parse(this.rawJsonText);
                    this.questionsList = Array.isArray(parsed) ? parsed : [];
                } catch (e) {
                    alert('JSON tidak valid, tidak bisa menampilkan preview.');
                    return;
                }
            }

            if (this.questionsList.length === 0) {
                alert('Belum ada soal untuk dipreview.');
                return;
            }

            this.openExercisePreview = true;
            this.$nextTick(() => {
                const el = document.getElementById('exercise-preview-container');
                if (el && window.renderMathInElement) {
                    renderMathInElement(el, {
                        delimiters: [
                            {left: '$$', right: '$$', display: true},
                            {left: '$', right: '$', display: false}
                        ],
                        throwOnError: false
                    });
                }
            });
        },

        getQuestionLabel(type) {
            const labels = { 'pg': 'Pilihan Ganda', 'pgk': 'PG Kompleks', 'bs': 'Benar / Salah', 'isian': 'Isian Singkat', 'menjodohkan': 'Menjodohkan' };
            return labels[type] || 'Soal';
        },

        openLatihan(action, isEdit, title, questionsJson) {
            this.formAction = action;
            this.editMode = isEdit;
            this.exerciseTitle = title;
            this.isJsonMode = false;

            try {
                this.questionsList = questionsJson && questionsJson.trim() !== '' ? JSON.parse(questionsJson) : [];
                if(!Array.isArray(this.questionsList)) this.questionsList = [];
            } catch(e) {
                this.questionsList = [];
                console.error('JSON Error:', e);
            }

            this.showQuestionEditor = false;
            this.openExerciseModal = true;
        },

        openQuestionForm(idx = -1) {
            this.editQuestionIndex = idx;
            if (idx > -1) {
                this.qForm = JSON.parse(JSON.stringify(this.questionsList[idx]));
                if(!this.qForm.image) this.qForm.image = '';
                if(!this.qForm.options) this.qForm.options = [];
                if(!this.qForm.pairs) this.qForm.pairs = [];
            } else {
                this.qForm = {
                    type: 'pg', text: '', image: '', answer: 'A',
                    options: [
                        {id: 'A', text: ''}, {id: 'B', text: ''}, {id: 'C', text: ''}, {id: 'D', text: ''}
                    ],
                    pairs: []
                };
            }
            this.showQuestionEditor = true;
        },

        changeQuestionType() {
            let t = this.qForm.type;
            if (t === 'pg' || t === 'pgk') {
                this.qForm.options = [{id:'A', text:''}, {id:'B', text:''}, {id:'C', text:''}, {id:'D', text:''}];
                this.qForm.answer = t === 'pg' ? 'A' : [];
            } else if (t === 'bs') {
                this.qForm.answer = 'Benar';
            } else if (t === 'isian') {
                this.qForm.answer = '';
            } else if (t === 'menjodohkan') {
                this.qForm.pairs = [{key:'', val:''}, {key:'', val:''}];
            }
        },

        // UPLOAD GAMBAR AJAX (V4)
        async uploadImageToServer(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.isUploading = true;
            const formData = new FormData();
            formData.append('image', file);

            const metaToken = document.querySelector('meta[name=\x22csrf-token\x22]');
            const csrfToken = metaToken ? metaToken.getAttribute('content') : '';

            try {
                const response = await fetch('{{ route('admin.upload.image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    this.qForm.image = data.url;
                } else {
                    alert('Gagal mengunggah gambar: ' + (data.message || 'Error tidak diketahui'));
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan saat mengunggah gambar.');
                console.error(error);
            } finally {
                this.isUploading = false;
                event.target.value = '';
            }
        },

        saveQuestion() {
            let qToSave = {
                type: this.qForm.type,
                text: this.qForm.text,
                image: this.qForm.image
            };

            if (qToSave.type === 'pg' || qToSave.type === 'pgk') {
                qToSave.options = this.qForm.options;
                qToSave.answer = this.qForm.answer;
            } else if (qToSave.type === 'bs' || qToSave.type === 'isian') {
                qToSave.answer = this.qForm.answer;
            } else if (qToSave.type === 'menjodohkan') {
                qToSave.pairs = this.qForm.pairs;
                qToSave.answer = {};
                this.qForm.pairs.forEach(p => { qToSave.answer[p.key] = p.val; });
            }

            if (this.editQuestionIndex > -1) {
                this.questionsList[this.editQuestionIndex] = qToSave;
            } else {
                this.questionsList.push(qToSave);
            }
            this.showQuestionEditor = false;
        },

        deleteQuestion(idx) {
            if(confirm('Yakin ingin menghapus soal ini?')) {
                this.questionsList.splice(idx, 1);
            }
        }
    }" class="py-12 bg-slate-50 min-h-screen flex-1">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Notifikasi -->
        @if(session('success'))
        <div
            class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500"></i><span
                    class="font-medium">{{ session('success') }}</span></div>
            <button onclick="this.parentElement.style.display='none'"><i
                    class="fas fa-times text-emerald-500"></i></button>
        </div>
        @endif

        <a href="{{ route('admin.courses.subject.detail', ['grade_id' => $grade->id, 'menu_id' => $menu->id, 'subject_id' => $subject->id]) }}"
            class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors group mb-8">
            <div
                class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
            </div>
            Kembali ke Daftar Bab {{ $subject->name }}
        </a>

        <!-- Header -->
        <div class="mb-10 bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 text-[12rem] font-black text-slate-50 z-0 select-none">{{
                $chapter->order_num }}</div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <div>
                    <div
                        class="inline-flex items-center gap-2 text-indigo-600 font-bold uppercase tracking-widest text-xs mb-3 bg-indigo-50 px-4 py-1.5 rounded-full border border-indigo-100">
                        Bab {{ $chapter->order_num }} &bull; {{ $subject->name }}
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight mb-2">{{ $chapter->name }}
                    </h1>
                </div>
                <form action="{{ route('admin.courses.chapter.destroy', $chapter->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus bab ini beserta isinya?');">
                    @csrf @method('DELETE') <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <button type="submit"
                        class="bg-white border border-slate-200 hover:bg-red-50 text-slate-700 hover:text-red-700 px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                        <i class="fas fa-trash-alt text-red-500"></i> Hapus Bab
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Konten -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- MATERI BACAAN -->
            <div>
                <div class="flex flex-wrap items-center justify-between mb-6 gap-3">
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-lg">
                            <i class="fas fa-file-alt"></i>
                        </div> Materi Bacaan
                    </h2>

                    <!-- TAMBAHAN: Tombol Atur Urutan dan Tambah Materi -->
                    <div class="flex gap-2">
                        @if($chapter->materials->count() > 1)
                        <a href="{{ route('admin.courses.material.sort', $chapter->id) }}"
                            class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm transition-colors shadow-sm inline-flex items-center">
                            <i class="fas fa-sort-numeric-down mr-2"></i> Urutkan
                        </a>
                        @endif

                        <button
                            @click="openModal('{{ route('admin.courses.material.store', $chapter->id) }}', false, '', '')"
                            class="bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white px-4 py-2 rounded-xl font-bold text-sm transition-colors">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
                <div class="space-y-4">
                    <!-- Pastikan materi dilooping berdasarkan order_num yang baru -->
                    @forelse($chapter->materials->sortBy('order_num') as $material)
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-indigo-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                                <span class="font-bold text-sm">{{ $material->order_num }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $material->title }}</h3>
                                <p class="text-xs text-slate-500 mt-1">Dibuat pada {{ $material->created_at->format('d M
                                    Y') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" data-title="{{ $material->title }}"
                                data-content="{{ $material->content }}"
                                data-action="{{ route('admin.courses.material.update', $material->id) }}"
                                @click="openModal($el.dataset.action, true, $el.dataset.title, $el.dataset.content)"
                                class="w-8 h-8 rounded-full bg-slate-50 hover:bg-blue-100 text-slate-400 hover:text-blue-600 flex items-center justify-center">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <form action="{{ route('admin.courses.material.destroy', $material->id) }}" method="POST"
                                onsubmit="return confirm('Hapus materi?');" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-full bg-slate-50 hover:bg-red-100 text-slate-400 hover:text-red-600 flex items-center justify-center"><i
                                        class="fas fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div
                        class="bg-white p-8 rounded-2xl border-2 border-dashed border-slate-200 text-center text-slate-500">
                        Belum ada materi untuk bab ini.</div>
                    @endforelse
                </div>
            </div>

            <!-- LATIHAN SOAL -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-lg">
                            <i class="fas fa-tasks"></i>
                        </div> Latihan Soal
                    </h2>
                    <button
                        @click="openLatihan('{{ route('admin.courses.exercise.store', $chapter->id) }}', false, '', '')"
                        class="bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white px-4 py-2 rounded-xl font-bold text-sm transition-colors">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </button>
                </div>
                <div class="space-y-4">
                    @forelse($chapter->exercises as $exercise)
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-emerald-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                                <i class="fas fa-pencil-ruler"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $exercise->title }}</h3>
                                @php
                                $qCount = 0;
                                if(!empty($exercise->questions)) {
                                $decoded = json_decode($exercise->questions);
                                if(is_array($decoded)) $qCount = count($decoded);
                                }
                                @endphp
                                <p class="text-xs text-slate-500 mt-1">{{ $qCount }} Butir Soal</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" data-title="{{ $exercise->title }}"
                                data-questions="{{ $exercise->questions }}"
                                data-action="{{ route('admin.courses.exercise.update', $exercise->id) }}"
                                @click="openLatihan($el.dataset.action, true, $el.dataset.title, $el.dataset.questions)"
                                class="w-8 h-8 rounded-full bg-slate-50 hover:bg-blue-100 text-slate-400 hover:text-blue-600 flex items-center justify-center">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <!-- Tombol Preview Langsung dari List -->
                            <button type="button" data-questions="{{ $exercise->questions }}"
                                @click="rawJsonText = $el.dataset.questions; isJsonMode = true; renderPreviewMath()"
                                class="w-8 h-8 rounded-full bg-slate-50 hover:bg-indigo-100 text-slate-400 hover:text-indigo-600 flex items-center justify-center">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            <form action="{{ route('admin.courses.exercise.destroy', $exercise->id) }}" method="POST"
                                onsubmit="return confirm('Hapus Latihan Soal ini?');" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-full bg-slate-50 hover:bg-red-100 text-slate-400 hover:text-red-600 flex items-center justify-center"><i
                                        class="fas fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div
                        class="bg-white p-8 rounded-2xl border-2 border-dashed border-slate-200 text-center text-slate-500">
                        Belum ada latihan soal untuk bab ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL FORM MATERI -->
    <!-- ============================================== -->
    <div x-show="openMaterialModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div x-show="openMaterialModal" x-transition.opacity
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="openMaterialModal" @click.away="openMaterialModal = false"
                class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-slate-200">
                <div class="bg-white px-6 pb-4 pt-6 sm:p-8 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800"
                            x-text="editMode ? 'Edit Materi Bacaan' : 'Tambah Materi Baru'"></h3>
                        <button @click="openMaterialModal = false" type="button"
                            class="text-slate-400 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
                    </div>
                    <form :action="formAction" method="POST"
                        @submit="if(tinymce.get('materi_editor')) { tinymce.get('materi_editor').save(); }">
                        @csrf <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Judul Materi</label>
                                <input type="text" name="title" x-model="materialTitle" required
                                    class="w-full rounded-xl border-slate-300 px-4 py-3 bg-slate-50">
                            </div>
                            <div>
                                <div class="flex space-x-1 bg-slate-100 p-1 rounded-xl mb-3">
                                    <button type="button" @click="activeTab = 'write'"
                                        :class="activeTab === 'write' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500'"
                                        class="flex-1 py-2 text-sm font-bold rounded-lg">Rich Editor</button>
                                    <button type="button" @click="previewContent()"
                                        :class="activeTab === 'preview' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500'"
                                        class="flex-1 py-2 text-sm font-bold rounded-lg">Live Preview (LaTeX)</button>
                                </div>
                                <div x-show="activeTab === 'write'"><textarea name="content"
                                        id="materi_editor"></textarea></div>
                                <div x-show="activeTab === 'preview'" style="display: none;">
                                    <div class="w-full rounded-xl border border-slate-200 bg-white p-6 min-h-[300px] max-h-[400px] overflow-y-auto"
                                        x-html="materialContent"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <button type="button" @click="openMaterialModal = false"
                                class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl font-bold text-sm">Batal</button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm">Simpan
                                Materi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL FORM LATIHAN SOAL (VISUAL BUILDER)       -->
    <!-- ============================================== -->
    <div x-show="openExerciseModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="openExerciseModal" x-transition.opacity
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="openExerciseModal" @click.away="if(!showQuestionEditor) openExerciseModal = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="relative transform overflow-hidden rounded-[2rem] bg-slate-50 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-slate-200">

                <div class="px-6 pb-4 pt-6 sm:p-8 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800"
                            x-text="editMode ? 'Kelola Latihan' : 'Tambah Latihan Baru'"></h3>
                        <button type="button" @click="openExerciseModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 hover:bg-red-100 text-slate-500 hover:text-red-600 transition-colors"><i
                                class="fas fa-times"></i></button>
                    </div>

                    <!-- FORM UTAMA -->
                    <form :action="formAction" method="POST">
                        @csrf <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">

                        <!-- Input Hidden Pembawa Array Visual -> JSON String -->
                        <input type="hidden" name="questions" :value="JSON.stringify(questionsList)">

                        <div class="space-y-6">
                            <!-- Input Judul Latihan -->
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Judul Latihan</label>
                                <input type="text" name="title" x-model="exerciseTitle" required
                                    class="w-full rounded-xl border-slate-200 focus:border-emerald-500 px-4 py-3 bg-slate-50"
                                    placeholder="Contoh: Penilaian Harian Bab 1">
                            </div>

                            <!-- DAFTAR SOAL & JSON EDITOR -->
                            <div x-show="!showQuestionEditor"
                                class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-bold text-slate-700">Daftar Soal (<span
                                            x-text="questionsList.length"></span>)</h4>

                                    <div class="flex gap-2">
                                        <!-- Tombol Toggle Mode -->
                                        <button type="button"
                                            @click="isJsonMode ? switchToVisualMode() : switchToJsonMode()"
                                            class="px-3 py-2 rounded-xl text-xs font-bold transition-all"
                                            :class="isJsonMode ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600'">
                                            <i class="fas" :class="isJsonMode ? 'fa-eye' : 'fa-code'"></i>
                                            <span x-text="isJsonMode ? 'Lihat Visual' : 'Edit JSON'"></span>
                                        </button>

                                        <button x-show="!isJsonMode" type="button" @click="openQuestionForm(-1)"
                                            class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-600 hover:text-white transition-colors">
                                            <i class="fas fa-plus mr-1"></i> Buat Soal
                                        </button>

                                        <button x-show="questionsList.length > 0 || isJsonMode" type="button"
                                            @click.stop="renderPreviewMath()"
                                            class="px-3 py-2 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all">
                                            <i class="fas fa-eye mr-1"></i> Preview
                                        </button>
                                    </div>
                                </div>

                                <!-- TAMPILAN VISUAL (KARTU-KARTU) -->
                                <div x-show="!isJsonMode" class="space-y-3 max-h-[50vh] overflow-y-auto pr-2">
                                    <template x-for="(q, idx) in questionsList" :key="idx">
                                        <div
                                            class="p-4 border border-slate-100 bg-slate-50 rounded-xl flex items-start justify-between group">
                                            <div class="flex-1 pr-4">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span
                                                        class="bg-slate-200 text-slate-600 text-[10px] px-2 py-0.5 rounded font-bold"
                                                        x-text="'No. ' + (idx+1)"></span>
                                                    <span
                                                        class="text-emerald-600 text-[10px] font-bold uppercase tracking-wider"
                                                        x-text="getQuestionLabel(q.type)"></span>
                                                </div>
                                                <div class="text-sm font-medium text-slate-700 line-clamp-2"
                                                    x-text="q.text || '(Soal belum ada teks)'"></div>
                                                <div x-show="q.image" class="mt-2 text-xs text-blue-500 font-bold"><i
                                                        class="fas fa-image"></i> Berisi Gambar</div>
                                            </div>
                                            <div
                                                class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button type="button" @click="openQuestionForm(idx)"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-blue-500 hover:bg-blue-50"><i
                                                        class="fas fa-edit"></i></button>
                                                <button type="button" @click="deleteQuestion(idx)"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-red-500 hover:bg-red-50"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="questionsList.length === 0"
                                        class="p-8 text-center border-2 border-dashed border-slate-200 rounded-xl">
                                        <p class="text-slate-400 text-sm">Belum ada soal. Klik "Buat Soal" atau "Edit
                                            JSON".</p>
                                    </div>
                                </div>

                                <!-- TAMPILAN RAW JSON EDITOR (TEXTAREA) -->
                                <div x-show="isJsonMode" style="display: none;">
                                    <div class="mb-2 text-[10px] text-amber-600 font-bold bg-amber-50 p-2 rounded-lg">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Perhatian: Pastikan format JSON
                                        benar sebelum beralih ke mode visual.
                                    </div>
                                    <textarea x-model="rawJsonText"
                                        class="w-full h-[45vh] font-mono text-xs p-4 bg-slate-900 text-emerald-400 rounded-xl border-none focus:ring-2 focus:ring-emerald-500"
                                        spellcheck="false" placeholder="Paste JSON soal di sini..."></textarea>
                                </div>
                            </div>

                            <!-- FORM EDITOR SOAL INDIVIDUAL -->
                            <div x-show="showQuestionEditor" style="display: none;"
                                class="bg-white p-6 rounded-2xl border-2 border-emerald-400 shadow-lg relative">
                                <h4 class="font-black text-lg text-emerald-800 mb-4 pb-4 border-b border-slate-100"
                                    x-text="editQuestionIndex > -1 ? 'Edit Soal' : 'Soal Baru'"></h4>

                                <div class="space-y-4">
                                    <!-- Tipe Soal -->
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tipe
                                            Soal</label>
                                        <select x-model="qForm.type" @change="changeQuestionType()"
                                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 px-4 py-3 bg-slate-50 text-sm font-bold text-slate-700">
                                            <option value="pg">Pilihan Ganda (1 Jawaban)</option>
                                            <option value="pgk">Pilihan Ganda Kompleks (Lebih dari 1 Jawaban)</option>
                                            <option value="bs">Benar / Salah</option>
                                            <option value="isian">Isian Singkat</option>
                                            <option value="menjodohkan">Menjodohkan</option>
                                        </select>
                                    </div>

                                    <!-- Pertanyaan -->
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pertanyaan
                                            (Mendukung LaTeX $$...$$)</label>
                                        <textarea x-model="qForm.text" rows="3"
                                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 px-4 py-3 bg-slate-50 text-sm"
                                            placeholder="Tuliskan pertanyaanmu di sini..."></textarea>
                                    </div>

                                    <!-- Gambar Pendukung -->
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Gambar
                                            Pendukung (Otomatis dikonversi)</label>
                                        <div class="flex gap-2 items-center">
                                            <input type="text" x-model="qForm.image"
                                                class="flex-1 rounded-xl border-slate-300 focus:border-emerald-500 px-4 py-2 bg-slate-50 text-sm"
                                                placeholder="URL Gambar atau klik tombol Upload...">
                                            <div class="relative shrink-0">
                                                <input type="file" @change="uploadImageToServer($event)"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                    :disabled="isUploading">
                                                <button type="button"
                                                    class="px-4 py-2 bg-emerald-100 text-emerald-700 font-bold rounded-xl text-sm flex items-center gap-2 hover:bg-emerald-200 transition-colors"
                                                    :class="isUploading ? 'opacity-70 cursor-not-allowed' : ''">
                                                    <i class="fas"
                                                        :class="isUploading ? 'fa-spinner fa-spin' : 'fa-cloud-upload-alt'"></i>
                                                    <span
                                                        x-text="isUploading ? 'Mengonversi...' : 'Upload Foto'"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Preview Mini Gambar -->
                                        <div x-show="qForm.image" style="display: none;"
                                            class="mt-3 relative inline-block">
                                            <img :src="qForm.image"
                                                class="h-24 rounded-lg border-2 border-slate-200 shadow-sm object-cover bg-white p-1">
                                            <button type="button" @click="qForm.image = ''"
                                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow-md hover:bg-red-600 transition-all"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </div>

                                    <!-- DYNAMIC AREA (Tergantung Tipe Soal) -->
                                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl mt-4">
                                        <!-- AREA PG / PG KOMPLEKS -->
                                        <div x-show="qForm.type === 'pg' || qForm.type === 'pgk'">
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Opsi
                                                Jawaban (Pilih/Centang untuk Kunci)</label>
                                            <div class="space-y-2">
                                                <template x-for="(opt, idx) in qForm.options" :key="idx">
                                                    <div class="flex items-center gap-3">
                                                        <input :type="qForm.type === 'pg' ? 'radio' : 'checkbox'"
                                                            :value="opt.id" x-model="qForm.answer"
                                                            class="w-5 h-5 text-emerald-500 focus:ring-emerald-500 border-slate-300 rounded">
                                                        <span class="font-bold text-slate-500 w-4 text-center"
                                                            x-text="opt.id"></span>
                                                        <input type="text" x-model="opt.text"
                                                            class="flex-1 rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-emerald-500">
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- AREA BENAR/SALAH -->
                                        <div x-show="qForm.type === 'bs'">
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Kunci
                                                Jawaban yang Benar</label>
                                            <select x-model="qForm.answer"
                                                class="w-full rounded-xl border-slate-300 focus:border-emerald-500 px-4 py-2">
                                                <option value="Benar">Benar</option>
                                                <option value="Salah">Salah</option>
                                            </select>
                                        </div>

                                        <!-- AREA ISIAN SINGKAT -->
                                        <div x-show="qForm.type === 'isian'">
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Kunci
                                                Jawaban Tepat</label>
                                            <input type="text" x-model="qForm.answer"
                                                class="w-full rounded-xl border-slate-300 focus:border-emerald-500 px-4 py-2">
                                            <p class="text-xs text-slate-400 mt-1">Sistem otomatis mengabaikan spasi dan
                                                huruf besar/kecil saat mengoreksi.</p>
                                        </div>

                                        <!-- AREA MENJODOHKAN -->
                                        <div x-show="qForm.type === 'menjodohkan'">
                                            <div class="flex justify-between items-center mb-3">
                                                <label
                                                    class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pasangan
                                                    Menjodohkan</label>
                                                <button type="button" @click="qForm.pairs.push({key:'', val:''})"
                                                    class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded"><i
                                                        class="fas fa-plus"></i> Tambah</button>
                                            </div>
                                            <div class="space-y-3">
                                                <template x-for="(pair, idx) in qForm.pairs" :key="idx">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" x-model="pair.key"
                                                            placeholder="Pernyataan Kiri"
                                                            class="flex-1 rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-purple-500">
                                                        <i class="fas fa-arrow-right text-slate-300 text-xs"></i>
                                                        <input type="text" x-model="pair.val"
                                                            placeholder="Jawaban Kanan"
                                                            class="flex-1 rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-purple-500">
                                                        <button type="button" @click="qForm.pairs.splice(idx, 1)"
                                                            class="w-8 h-8 rounded bg-red-50 text-red-500 flex items-center justify-center"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>
                                                </template>
                                                <div x-show="qForm.pairs.length === 0"
                                                    class="text-sm text-slate-400 italic">Klik tambah untuk membuat
                                                    pasangan baru.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showQuestionEditor = false"
                                        class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-100">Batal
                                        Edit</button>
                                    <button type="button" @click="saveQuestion()"
                                        class="px-5 py-2 rounded-xl text-sm font-bold bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm"><i
                                            class="fas fa-check mr-1"></i> Simpan ke Daftar</button>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL SUBMIT UTAMA -->
                        <div x-show="!showQuestionEditor"
                            class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-200">
                            <button type="button" @click="openExerciseModal = false"
                                class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm">Batal</button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-slate-800 text-white rounded-xl font-black text-sm hover:bg-black shadow-lg">
                                <i class="fas fa-cloud-upload-alt mr-2"></i> <span
                                    x-text="editMode ? 'Update Seluruh Latihan' : 'Simpan Latihan Baru'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL PREVIEW TAMPILAN SISWA (TELEPORT)        -->
    <!-- ============================================== -->
    <template x-teleport="body">
        <div x-show="openExercisePreview" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto">
            <div x-show="openExercisePreview" x-transition.opacity
                class="fixed inset-0 bg-slate-900/90 backdrop-blur-md"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="openExercisePreview" @click.away="openExercisePreview = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative bg-slate-50 w-full max-w-3xl rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

                    <!-- Header Preview -->
                    <div class="p-6 bg-white border-b border-slate-200 flex items-center justify-between shrink-0">
                        <div>
                            <h4 class="font-black text-slate-800 text-xl" x-text="exerciseTitle || 'Preview Latihan'">
                            </h4>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Simulasi Tampilan
                                Siswa</p>
                        </div>
                        <button @click="openExercisePreview = false"
                            class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 hover:bg-red-100 hover:text-red-600 transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Konten Preview (Scrollable) -->
                    <div id="exercise-preview-container" class="p-6 overflow-y-auto space-y-6 bg-slate-50">
                        <template x-for="(q, idx) in questionsList" :key="idx">
                            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <span
                                        class="bg-emerald-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter"
                                        x-text="'SOAL ' + (idx + 1)"></span>
                                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest"
                                        x-text="getQuestionLabel(q.type)"></span>
                                </div>

                                <!-- Gambar Soal -->
                                <div x-show="q.image" class="mb-4 rounded-xl overflow-hidden border border-slate-100">
                                    <img :src="q.image" class="w-full max-h-64 object-contain bg-slate-50">
                                </div>

                                <!-- Teks Soal -->
                                <div class="text-lg font-bold text-slate-800 mb-6 leading-relaxed" x-html="q.text">
                                </div>

                                <!-- Pilihan Jawaban (Simulasi) -->
                                <div class="space-y-2">
                                    <template x-if="q.type === 'pg' || q.type === 'pgk'">
                                        <template x-for="opt in q.options">
                                            <div
                                                class="flex items-center p-3 border-2 border-slate-100 rounded-xl text-sm text-slate-600 font-medium bg-slate-50/50">
                                                <span
                                                    class="w-6 h-6 rounded bg-white border border-slate-200 flex items-center justify-center mr-3 text-[10px] font-black"
                                                    x-html="opt.id"></span>
                                                <span x-html="opt.text"></span>
                                            </div>
                                        </template>
                                    </template>

                                    <template x-if="q.type === 'bs'">
                                        <div class="flex gap-2">
                                            <div
                                                class="flex-1 p-3 border-2 border-slate-100 rounded-xl text-center font-bold text-slate-600 bg-slate-50/50">
                                                Benar</div>
                                            <div
                                                class="flex-1 p-3 border-2 border-slate-100 rounded-xl text-center font-bold text-slate-600 bg-slate-50/50">
                                                Salah</div>
                                        </div>
                                    </template>

                                    <template x-if="q.type === 'isian'">
                                        <div
                                            class="p-3 border-2 border-dashed border-slate-200 rounded-xl text-slate-400 text-sm italic">
                                            Kolom input jawaban...</div>
                                    </template>

                                    <template x-if="q.type === 'menjodohkan'">
                                        <div class="space-y-2">
                                            <template x-for="pair in q.pairs">
                                                <div
                                                    class="flex items-center justify-between p-2 bg-slate-100 rounded-lg text-xs font-bold text-slate-600">
                                                    <span x-html="pair.key"></span>
                                                    <i class="fas fa-arrow-right mx-2 text-slate-300"></i>
                                                    <span class="text-indigo-600">Pilih Pasangan...</span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Footer Preview -->
                    <div class="p-4 bg-white border-t border-slate-100 text-center shrink-0">
                        <button @click="openExercisePreview = false"
                            class="px-8 py-2 bg-slate-800 text-white font-bold rounded-xl text-sm">Tutup
                            Preview</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div> <!-- Penutup DIV x-data UTAMA -->

@endsection