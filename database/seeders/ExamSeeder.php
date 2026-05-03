<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamSeeder extends Seeder
{
    public function run()
    {
        $mathSD = Subject::where('slug', 'matematika')->first();

        if (! $mathSD) {
            return;
        }

        Exam::create([
            'subject_id' => $mathSD->id,
            'title' => 'Try Out Persiapan PAS Semester 1',
            'slug' => Str::slug('Try Out Persiapan PAS Semester 1'),
            'description' => 'Simulasi ujian akhir semester. Pilih salah satu link server di bawah ini jika server utama penuh.',
            'links' => [
                ['name' => 'Link Server 1 (Google Form)', 'url' => 'https://forms.gle/contoh1'],
                ['name' => 'Link Server 2 (Quizizz)', 'url' => 'https://quizizz.com/contoh2'],
                ['name' => 'Link Cadangan', 'url' => 'https://example.com'],
            ],
            'is_active' => true,
        ]);
    }
}
