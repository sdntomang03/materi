<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\EducationLevel;
use App\Models\Exam;
use App\Models\Exercise;
use App\Models\Grade;
use App\Models\LevelMenu;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BimbelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Tingkat (Level)
        $sd = EducationLevel::create(['name' => 'SD', 'slug' => 'sd']);
        $smp = EducationLevel::create(['name' => 'SMP', 'slug' => 'smp']);
        $sma = EducationLevel::create(['name' => 'SMA', 'slug' => 'sma']);

        // 2. Buat Data Kelas (Grades)
        $kelas4SD = Grade::create(['education_level_id' => $sd->id, 'name' => 'Kelas 4', 'slug' => 'kelas-4', 'order_num' => 4]);
        $kelas5SD = Grade::create(['education_level_id' => $sd->id, 'name' => 'Kelas 5', 'slug' => 'kelas-5', 'order_num' => 5]);
        $kelas7SMP = Grade::create(['education_level_id' => $smp->id, 'name' => 'Kelas 7', 'slug' => 'kelas-7', 'order_num' => 7]);

        // 3. Buat Data Mata Pelajaran
        $mathSD = Subject::create([
            'grade_id' => $kelas4SD->id,
            'name' => 'Matematika',
            'slug' => Str::slug('Matematika Kelas 4 SD'),
            'icon' => 'fas fa-calculator',
        ]);

        $ipaSD = Subject::create([
            'grade_id' => $kelas4SD->id,
            'name' => 'Ilmu Pengetahuan Alam',
            'slug' => Str::slug('IPA Kelas 4 SD'),
            'icon' => 'fas fa-flask',
        ]);

        // 4. Buat Bab untuk Matematika Kelas 4 SD
        $bab1Math = Chapter::create(['subject_id' => $mathSD->id, 'name' => 'Bab 1: Pecahan Dasar', 'order_num' => 1]);
        $bab2Math = Chapter::create(['subject_id' => $mathSD->id, 'name' => 'Bab 2: Bangun Datar', 'order_num' => 2]);

        // 5. Buat Materi
        Material::create([
            'chapter_id' => $bab1Math->id,
            'title' => 'Penjumlahan Pecahan Berpenyebut Sama',
            'slug' => Str::slug('Penjumlahan Pecahan Berpenyebut Sama'),
            'meta_description' => 'Materi pecahan penjumlahan dan pengurangan pecahan berpenyebut sama.',
            'meta_keywords' => 'penjumlahan pecahan, pecahan berpenyebut sama, materi matematika kelas 4 sd',
            'order_num' => 1,
            'content' => '
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-emerald-600 mb-4 border-b-2 border-emerald-100 pb-2">Menjumlahkan Pecahan</h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">Jika penyebutnya sudah sama, kamu hanya perlu menjumlahkan pembilangnya!</p>
                    <div class="bg-emerald-50 text-emerald-800 p-6 rounded-xl text-center overflow-x-auto">
                        $$\\frac{1}{5} + \\frac{2}{5} = \\frac{1 + 2}{5} = \\frac{3}{5}$$
                    </div>
                </div>
            ',
        ]);

        // ==========================================
        // 6. Buat Latihan (Exercises) dengan Kolom JSON
        // ==========================================
        Exercise::create([
            'chapter_id' => $bab1Math->id,
            'title' => 'Latihan Soal: Pecahan Menyenangkan',
            'slug' => Str::slug('Latihan Soal Pecahan Kelas 4'),
            'duration_minutes' => 30,
            // JSON SOAL
            'questions' => json_encode([
                // Soal 1: Pilihan Ganda (KaTeX)
                [
                    'type' => 'pg',
                    'text' => 'Hasil dari $$ \\frac{1}{2} + \\frac{1}{4} $$ adalah...',
                    'options' => [
                        ['id' => 'A', 'text' => '$$ \\frac{3}{4} $$'],
                        ['id' => 'B', 'text' => '$$ \\frac{2}{6} $$'],
                        ['id' => 'C', 'text' => '$$ \\frac{2}{4} $$'],
                    ],
                    'answer' => 'A',
                ],
                // Soal 2: PG Kompleks / Checkbox (Bisa pilih > 1)
                [
                    'type' => 'pgk',
                    'text' => 'Manakah pecahan di bawah ini yang senilai dengan $$ \\frac{1}{2} $$? (Pilih semua yang benar)',
                    'options' => [
                        ['id' => 'A', 'text' => '$$ \\frac{2}{4} $$'],
                        ['id' => 'B', 'text' => '$$ \\frac{3}{7} $$'],
                        ['id' => 'C', 'text' => '$$ \\frac{5}{10} $$'],
                        ['id' => 'D', 'text' => '$$ \\frac{50}{100} $$'],
                    ],
                    'answer' => ['A', 'C', 'D'],
                ],
                // Soal 3: Benar / Salah
                [
                    'type' => 'bs',
                    'text' => 'Pernyataan: Pada pecahan $$ \\frac{3}{7} $$, angka 7 disebut sebagai pembilang.',
                    'answer' => 'Salah',
                ],
                // Soal 4: Isian Singkat
                [
                    'type' => 'isian',
                    'text' => 'Pecahan yang memiliki pembilang 1 dan penyebut 3 ditulis dengan (ketik tanpa spasi)...',
                    'answer' => '1/3',
                ],
                // Soal 5: Menjodohkan
                [
                    'type' => 'menjodohkan',
                    'text' => 'Jodohkan pecahan di sebelah kiri dengan bentuk desimalnya di sebelah kanan!',
                    'pairs' => [
                        ['key' => '1/2', 'val' => '0,5'],
                        ['key' => '1/4', 'val' => '0,25'],
                        ['key' => '3/4', 'val' => '0,75'],
                    ],
                    'answer' => ['1/2' => '0,5', '1/4' => '0,25', '3/4' => '0,75'],
                ],
            ]),
        ]);

        Exercise::create([
            'chapter_id' => $bab2Math->id,
            'title' => 'Latihan Soal: Bangun Datar Dasar',
            'slug' => Str::slug('Latihan Soal Bangun Datar Dasar Kelas 4'),
            'duration_minutes' => 45,
            'questions' => json_encode([
                [
                    'type' => 'pg',
                    'text' => 'Sebuah persegi memiliki sisi 4 cm. Berapakah kelilingnya?',
                    'options' => [
                        ['id' => 'A', 'text' => '8 cm'],
                        ['id' => 'B', 'text' => '16 cm'],
                        ['id' => 'C', 'text' => '12 cm'],
                    ],
                    'answer' => 'B',
                ],
            ]),
        ]);

        // ==========================================
        // 7. Buat Data Ujian (Exams)
        // ==========================================
        Exam::create([
            'subject_id' => $mathSD->id,
            'title' => 'Ujian Tengah Semester (UTS) - Matematika',
            'slug' => Str::slug('UTS Matematika Kelas 4 SD'),
            'description' => 'Kerjakan ujian berikut melalui link Google Form yang telah disediakan. Pastikan kamu memiliki koneksi internet yang stabil dan token ujian yang diberikan oleh gurumu.',
            'links' => json_encode([
                [
                    'title' => 'Link Soal Ujian (Google Form)',
                    'url' => 'https://docs.google.com/forms/', // Ganti dengan link asli nanti
                ],
                [
                    'title' => 'Download Kisi-Kisi (PDF)',
                    'url' => 'https://drive.google.com/', // Ganti dengan link asli nanti
                ],
            ]),
            'is_active' => true,
        ]);

        // ==========================================
        // 8. Buat Menu Aplikasi
        // ==========================================
        $sdMenus = [
            ['title' => 'Materi Pelajaran', 'slug' => 'materi', 'description' => 'Baca rangkuman materi lengkap.', 'icon' => 'fas fa-book-open', 'color_theme' => 'blue', 'order_num' => 1],
            ['title' => 'Latihan Soal', 'slug' => 'latihan', 'description' => 'Kerjakan latihan soal interaktif.', 'icon' => 'fas fa-pencil-alt', 'color_theme' => 'emerald', 'order_num' => 2],
            ['title' => 'Try Out Ujian', 'slug' => 'tryout', 'description' => 'Kerjakan simulasi ujian akhir.', 'icon' => 'fas fa-stopwatch', 'color_theme' => 'rose', 'order_num' => 3],
        ];
        foreach ($sdMenus as $menu) {
            $menu['education_level_id'] = $sd->id;
            LevelMenu::create($menu);
        }
    }
}
