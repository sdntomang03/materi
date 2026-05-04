<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\EducationLevel;
use App\Models\Exercise;
use App\Models\Grade;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    // Menampilkan Halaman Upload
    public function index()
    {
        return view('admin.import.index');
    }

    // Memproses File JSON
    public function store(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimetypes:application/json,text/plain|max:2048',
        ]);

        $file = $request->file('json_file');
        $jsonContent = file_get_contents($file->getRealPath());
        $data = json_decode($jsonContent, true);

        if (! $data) {
            return back()->with('error', 'Format JSON tidak valid atau file rusak.');
        }

        // Gunakan Transaction agar jika di tengah jalan gagal, data di-rollback (tidak ada data setengah matang)
        try {
            DB::beginTransaction();

            // 1. Cari atau Buat Level (SD/SMP/SMA)
            $level = EducationLevel::firstOrCreate(
                ['slug' => Str::slug($data['level'])],
                ['name' => strtoupper($data['level'])]
            );

            // 2. Cari atau Buat Kelas (Grade)
            $grade = Grade::firstOrCreate(
                ['education_level_id' => $level->id, 'slug' => Str::slug($data['grade'])],
                ['name' => $data['grade'], 'order_num' => (int) filter_var($data['grade'], FILTER_SANITIZE_NUMBER_INT)]
            );

            // 3. Buat Mata Pelajaran Baru
            $subject = Subject::create([
                'grade_id' => $grade->id,
                'name' => $data['subject']['name'],
                'slug' => Str::slug($data['subject']['name'].' '.$grade->name.' '.$level->name),
                'icon' => $data['subject']['icon'] ?? 'fas fa-book',
            ]);

            // 4. Looping Bab (Chapters)
            if (isset($data['chapters']) && is_array($data['chapters'])) {
                foreach ($data['chapters'] as $ch) {
                    $chapter = Chapter::create([
                        'subject_id' => $subject->id,
                        'name' => $ch['name'],
                        'order_num' => $ch['order_num'],
                    ]);

                    // 5. Looping Materi di dalam Bab
                    if (isset($ch['materials']) && is_array($ch['materials'])) {
                        foreach ($ch['materials'] as $mat) {
                            Material::create([
                                'chapter_id' => $chapter->id,
                                'title' => $mat['title'],
                                'slug' => Str::slug($mat['title'].'-'.uniqid()), // uniqid mencegah duplikat slug
                                'meta_description' => $mat['meta_description'] ?? null,
                                'meta_keywords' => $mat['meta_keywords'] ?? null,
                                'order_num' => $mat['order_num'] ?? 1,
                                'content' => $mat['content'],
                            ]);
                        }
                    }

                    // 6. Looping Latihan di dalam Bab
                    if (isset($ch['exercises']) && is_array($ch['exercises'])) {
                        foreach ($ch['exercises'] as $ex) {
                            Exercise::create([
                                'chapter_id' => $chapter->id,
                                'title' => $ex['title'],
                                'slug' => Str::slug($ex['title'].'-'.uniqid()),
                                'duration_minutes' => $ex['duration_minutes'] ?? 30,
                                'questions' => json_encode($ex['questions']), // Langsung simpan JSON array ke kolom DB
                            ]);
                        }
                    }
                }
            }

            DB::commit(); // Simpan permanen ke database

            return redirect()->route('admin.import.index')->with('success', "Data {$subject->name} beserta Bab dan Isinya berhasil diimpor!");

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error

            return back()->with('error', 'Gagal memproses data: '.$e->getMessage());
        }
    }
}
