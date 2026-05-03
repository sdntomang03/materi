@extends('layouts.latihan')

@section('title', ($exercise->title ?? 'Latihan') . ' - UjianPro')

@section('content')
<div class="relative pt-8 pb-20 bg-slate-50 min-h-screen flex-1">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <!-- Navigasi & Breadcrumbs -->
        <div class="mb-8">
            <!-- Tombol Kembali -->
            <a href="{{ route('subject.show', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug, 'subject_slug' => $subject->slug]) }}"
                class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-bold text-sm mb-4 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Bab
            </a>

            <!-- Breadcrumbs Lengkap -->
            <div class="flex flex-wrap items-center text-xs font-bold text-slate-400 uppercase tracking-widest gap-2">
                <span>{{ $level->name }}</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span>{{ $grade->name }}</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span>{{ $subject->name }}</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-emerald-500">Bab {{ $chapter->order_num ?? '-' }}: {{ $chapter->name ?? '-' }}</span>
            </div>
        </div>

        <!-- Header Ujian & Info Bab (TIDAK STICKY) -->
        <!-- Header Ujian (Format Kop Soal Tabel) -->
        <div id="quiz-header"
            class="bg-white rounded-[2rem] border-b-4 border-b-emerald-500 mb-8 shadow-sm overflow-hidden relative">

            <!-- Aksen Warna di atas header -->
            <div class="h-2 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

            <div class="p-6 sm:p-8 overflow-x-auto">
                <!-- Tabel Kop Soal -->
                <table
                    class="w-full max-w-3xl mx-auto border-2 border-emerald-100 text-sm sm:text-base rounded-xl border-collapse">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="bg-emerald-50 border-b-2 border-emerald-100 text-emerald-800 font-black text-xl sm:text-2xl p-4 text-center uppercase tracking-wider">
                                {{ $exercise->title ?? 'Soal Ulangan Harian' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        <!-- Baris Kurikulum -->
                        <tr class="border-b border-emerald-100">
                            <td class="py-3 px-4 font-bold bg-slate-50 w-1/3 border-r border-emerald-100">
                                Kurikulum
                            </td>
                            <td class="py-3 px-4 font-medium">
                                Merdeka
                            </td>
                        </tr>

                        <!-- Baris Mata Pelajaran -->
                        <tr class="border-b border-emerald-100">
                            <td class="py-3 px-4 font-bold bg-slate-50 border-r border-emerald-100">
                                Mata Pelajaran
                            </td>
                            <td class="py-3 px-4 font-medium">
                                {{ $subject->name ?? '-' }}
                            </td>
                        </tr>

                        <!-- Baris Bab -->
                        <tr class="border-b border-emerald-100">
                            <td class="py-3 px-4 font-bold bg-slate-50 border-r border-emerald-100 whitespace-nowrap">
                                Bab {{ $chapter->order_num ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-medium">
                                {{ $chapter->name ?? '-' }}
                            </td>
                        </tr>

                        <!-- Baris Sub Bab / Kategori -->
                        <tr>
                            <td class="py-3 px-4 font-bold bg-slate-50 border-r border-emerald-100 align-top">
                                Kategori
                            </td>
                            <td class="py-3 px-4 font-medium">
                                {{ $exercise->title ?? 'Latihan Mandiri' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Container Semua Soal -->
        <div id="all-questions-container" class="space-y-6 sm:space-y-8"></div>

        <!-- Area Tombol Selesai -->
        <div id="action-area" class="mt-12 flex flex-col items-center">
            <button id="btn-submit"
                class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-black text-lg px-12 py-4 rounded-2xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-1">
                <i class="fas fa-check-circle mr-2"></i> Selesai & Lihat Nilai
            </button>
        </div>

        <!-- MODAL HASIL -->
        <div id="result-modal"
            class="hidden fixed inset-0 bg-slate-900/80 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full text-center shadow-2xl animate-in zoom-in duration-300">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl font-black border-4 border-white shadow-inner"
                    id="final-score">0</div>
                <h2 class="text-2xl font-black text-slate-800 mb-2">Latihan Selesai!</h2>
                <p class="text-slate-500 mb-8 leading-relaxed" id="score-message"></p>

                <a href="{{ route('subject.show', ['level_slug' => $level->slug, 'grade_slug' => $grade->slug, 'menu_slug' => $menu->slug, 'subject_slug' => $subject->slug]) }}"
                    class="block w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                    <i class="fas fa-list-ul mr-2"></i> Kembali ke Daftar Bab
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Render Library KaTeX untuk Rumus -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

    // MENGAMBIL DATA SOAL LANGSUNG DARI DATABASE (JSON)
    const questions = {!! $exercise->questions ?? '[]' !!};

    // Jika soal kosong dari database, sembunyikan tombol selesai dan tampilkan pesan
    if (questions.length === 0) {
        document.getElementById('all-questions-container').innerHTML = `
            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm text-center">
                <div class="text-4xl text-slate-300 mb-4"><i class="fas fa-box-open"></i></div>
                <h3 class="text-xl font-black text-slate-700 mb-2">Belum Ada Soal</h3>
                <p class="text-slate-500">Guru belum menambahkan soal untuk latihan ini.</p>
            </div>`;
        document.getElementById('action-area').style.display = 'none';
        return; // Hentikan script
    }

    let userAnswers = {};

    function renderQuestions() {
        const container = document.getElementById('all-questions-container');
        let html = '';

        questions.forEach((q, idx) => {
            let inputHtml = '';
let imageHtml = '';
if (q.image && q.image.trim() !== '') {
                imageHtml = `
                    <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img src="${q.image}"
                             alt="Gambar Soal ${idx + 1}"
                             class="w-full max-h-[400px] object-contain mx-auto transition-transform hover:scale-[1.02] duration-300">
                    </div>`;
            }
            if (q.type === 'pg') {
                q.options.forEach(opt => {
                    inputHtml += `
                        <label class="flex items-center p-4 border-2 border-slate-100 rounded-xl mb-3 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-500 group">
                            <input type="radio" name="q${idx}" value="${opt.id}" class="hidden" onchange="saveAns(${idx}, '${opt.id}')">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center mr-3 font-bold group-hover:bg-emerald-100 transition-colors">${opt.id}</span>
                            <span class="text-slate-700">${opt.text}</span>
                        </label>`;
                });
            } else if (q.type === 'pgk') {
                q.options.forEach(opt => {
                    inputHtml += `
                        <label class="flex items-center p-4 border-2 border-slate-100 rounded-xl mb-3 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 group">
                            <input type="checkbox" name="q${idx}" value="${opt.id}" class="hidden" onchange="saveAnsPGK(${idx}, '${opt.id}')">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center mr-3 font-bold group-hover:bg-blue-100 transition-colors">${opt.id}</span>
                            <span class="text-slate-700">${opt.text}</span>
                        </label>`;
                });
            } else if (q.type === 'bs') {
                ['Benar', 'Salah'].forEach(val => {
                    inputHtml += `
                        <label class="flex items-center p-4 border-2 border-slate-100 rounded-xl mb-3 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:bg-amber-50 has-[:checked]:border-amber-400 group">
                            <input type="radio" name="q${idx}" value="${val}" class="hidden" onchange="saveAns(${idx}, '${val}')">
                            <span class="font-bold text-slate-700 group-hover:text-amber-600 transition-colors">${val}</span>
                        </label>`;
                });
            } else if (q.type === 'menjodohkan') {
                const shuffledVals = [...q.pairs.map(p => p.val)].sort(() => Math.random() - 0.5);
                inputHtml += `<div class="space-y-3 mt-4">`;
                q.pairs.forEach((p) => {
                    inputHtml += `
                        <div class="flex flex-col sm:flex-row items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div class="flex-1 font-bold text-slate-700 text-sm sm:text-base">${p.key}</div>
                            <div class="hidden sm:block text-slate-300"><i class="fas fa-arrows-alt-h"></i></div>
                            <select onchange="saveAnsMenjodohkan(${idx}, '${p.key}', this.value)"
                                    class="w-full sm:w-48 p-2 rounded-lg border-2 border-slate-200 focus:border-emerald-500 outline-none text-sm font-medium transition-all bg-white cursor-pointer hover:border-emerald-300">
                                <option value="">Pilih Pasangan...</option>
                                ${shuffledVals.map(v => `<option value="${v}">${v}</option>`).join('')}
                            </select>
                        </div>`;
                });
                inputHtml += `</div>`;
            } else if (q.type === 'isian') {
                inputHtml += `
                    <div class="relative">
                        <input type="text" oninput="saveAns(${idx}, this.value)"
                               class="w-full p-4 border-2 border-slate-100 rounded-xl focus:border-emerald-500 focus:bg-emerald-50/30 outline-none transition-all shadow-sm"
                               placeholder="Ketik jawabanmu di sini...">
                    </div>`;
            }

            html += `
                <div class="bg-white p-6 sm:p-8 rounded-[2rem] border sm:border border-slate-200 shadow-sm transition-all" id="card-${idx}">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-emerald-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter shadow-sm">Soal ${idx + 1}</span>
                        <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">${getLabel(q.type)}</span>
                    </div>
                    ${imageHtml}
                    <div class="text-lg sm:text-xl font-bold text-slate-800 mb-6 leading-relaxed">${q.text}</div>
                    <div class="space-y-1">${inputHtml}</div>
                    <div id="feedback-${idx}" class="mt-6 hidden p-4 rounded-2xl text-sm font-bold duration-300"></div>
                </div>`;
        });

        container.innerHTML = html;

        // Memanggil KaTeX setelah soal di-render ke layar
        setTimeout(() => {
            if (window.renderMathInElement) {
                renderMathInElement(container, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false}
                    ]
                });
            }
        }, 100);
    }

    function getLabel(type) {
        const labels = { 'pg': 'Pilihan Ganda', 'pgk': 'PG Kompleks', 'bs': 'Benar / Salah', 'isian': 'Isian Singkat', 'menjodohkan': 'Menjodohkan' };
        return labels[type] || 'Soal';
    }

    window.saveAns = (idx, val) => { userAnswers[idx] = val; };
    window.saveAnsPGK = (idx, val) => {
        if (!userAnswers[idx]) userAnswers[idx] = [];
        const pos = userAnswers[idx].indexOf(val);
        if (pos === -1) userAnswers[idx].push(val);
        else userAnswers[idx].splice(pos, 1);
    };
    window.saveAnsMenjodohkan = (idx, key, val) => {
        if (!userAnswers[idx]) userAnswers[idx] = {};
        userAnswers[idx][key] = val;
    };

    document.getElementById('btn-submit').addEventListener('click', () => {
        const answeredCount = Object.keys(userAnswers).length;
        if (answeredCount < questions.length) {
            if (!confirm('Beberapa soal belum dijawab. Yakin ingin menyelesaikan kuis?')) return;
        }

        let correctCount = 0;

        questions.forEach((q, idx) => {
            let isCorrect = false;
            let displayCorrect = "";
            const feedback = document.getElementById(`feedback-${idx}`);
            const card = document.getElementById(`card-${idx}`);

            if (q.type === 'pg' || q.type === 'bs') {
                isCorrect = userAnswers[idx] === q.answer;
                displayCorrect = q.answer;
            } else if (q.type === 'pgk') {
                const userSet = new Set(userAnswers[idx] || []);
                const ansSet = new Set(q.answer);
                isCorrect = userSet.size === ansSet.size && [...userSet].every(x => ansSet.has(x));
                displayCorrect = q.answer.join(', ');
            } else if (q.type === 'isian') {
                const userVal = (userAnswers[idx] || "").toLowerCase().replace(/\s+/g, '');
                const ansVal = q.answer.toLowerCase().replace(/\s+/g, '');
                isCorrect = userVal === ansVal;
                displayCorrect = q.answer;
            } else if (q.type === 'menjodohkan') {
                const userAns = userAnswers[idx] || {};
                const correctAns = q.answer;
                isCorrect = Object.keys(correctAns).every(k => userAns[k] === correctAns[k]);
                displayCorrect = Object.entries(q.answer).map(([k, v]) => `${k} → ${v}`).join(', ');
            }

            feedback.classList.remove('hidden');
            if (isCorrect) {
                correctCount++;
                feedback.innerHTML = `<i class="fas fa-check-circle mr-2"></i> Keren! Jawabanmu benar.`;
                feedback.className = "mt-6 p-4 rounded-2xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-100";
                card.classList.add('ring-2', 'ring-emerald-500', 'border-transparent', 'bg-emerald-50/10');
            } else {
                feedback.innerHTML = `<i class="fas fa-times-circle mr-2"></i> Belum tepat. Jawaban benar: <span class="underline">${displayCorrect}</span>`;
                feedback.className = "mt-6 p-4 rounded-2xl text-sm font-bold bg-rose-50 text-rose-700 border border-rose-100";
                card.classList.add('ring-2', 'ring-rose-500', 'border-transparent', 'bg-rose-50/10');
            }
        });

        const finalScore = Math.round((correctCount / questions.length) * 100);
        document.getElementById('final-score').textContent = finalScore;
        const msg = document.getElementById('score-message');

        if (finalScore === 100) msg.textContent = "Sempurna! Kamu menguasai bab ini dengan sangat baik!";
        else if (finalScore >= 75) msg.textContent = "Hasil yang hebat! Teruslah berlatih.";
        else msg.textContent = "Bagus sudah mencoba! Mari pelajari kembali materinya ya.";

        document.getElementById('result-modal').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    renderQuestions();
});
</script>
@endsection