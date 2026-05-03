<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\EducationLevel;
use App\Models\Exam;
use App\Models\Exercise;
use App\Models\Grade;
use App\Models\LevelMenu;
use App\Models\Material;
use App\Models\Subject;

class BelajarController extends Controller
{
    // 1. Beranda: Pilih Level (SD/SMP/SMA)
    public function index()
    {
        $levels = EducationLevel::orderBy('id', 'asc')->get();

        return view('home', compact('levels'));
    }

    // 2. Tampilkan Kelas (Cth: Klik SD -> Muncul Kelas 1 s/d 6)
    public function showGrades($level_slug)
    {
        $level = EducationLevel::where('slug', $level_slug)->firstOrFail();

        $grades = Grade::where('education_level_id', $level->id)
            ->orderBy('order_num', 'asc')
            ->get();

        return view('menu.grades', compact('level', 'grades'));
    }

    // 3. Tampilkan Menu (Cth: Klik Kelas 4 -> Muncul Menu Materi & Latihan)
    public function showMenu($level_slug, $grade_slug)
    {
        $level = EducationLevel::where('slug', $level_slug)->firstOrFail();

        $grade = Grade::where('slug', $grade_slug)
            ->where('education_level_id', $level->id)
            ->firstOrFail();

        // LevelMenu terikat pada education_level (Menu SD berlaku untuk semua kelas SD)
        $menus = LevelMenu::where('education_level_id', $level->id)
            ->orderBy('order_num', 'asc')
            ->get();

        return view('menu.menu', compact('level', 'grade', 'menus'));
    }

    // 4. Tampilkan Mapel (Cth: Klik Materi -> Muncul Matematika, B. Indo)
    public function showSubjects($level_slug, $grade_slug, $menu_slug)
    {
        $level = EducationLevel::where('slug', $level_slug)->firstOrFail();

        $grade = Grade::where('slug', $grade_slug)
            ->where('education_level_id', $level->id)
            ->firstOrFail();

        $menu = LevelMenu::where('slug', $menu_slug)
            ->where('education_level_id', $level->id)
            ->firstOrFail();

        // Ambil mapel KHUSUS untuk kelas ini saja
        $subjects = Subject::where('grade_id', $grade->id)->get();

        return view('menu.mapel', compact('level', 'grade', 'menu', 'subjects'));
    }

    // 5. Tampilkan Bab & Daftar Materi/Latihan
    public function showChapters($level_slug, $grade_slug, $menu_slug, $subject_slug)
    {
        $level = EducationLevel::where('slug', $level_slug)->firstOrFail();
        $grade = Grade::where('slug', $grade_slug)->where('education_level_id', $level->id)->firstOrFail();
        $menu = LevelMenu::where('slug', $menu_slug)->where('education_level_id', $level->id)->firstOrFail();

        $subject = Subject::where('slug', $subject_slug)
            ->where('grade_id', $grade->id)
            ->firstOrFail();

        if ($menu->slug === 'materi') {
            $chapters = Chapter::where('subject_id', $subject->id)->with('materials')->orderBy('order_num')->get();

            return view('materi.show-materi', compact('level', 'grade', 'menu', 'subject', 'chapters'));
        } elseif ($menu->slug === 'latihan') {
            $chapters = Chapter::where('subject_id', $subject->id)->with('exercises')->orderBy('order_num')->get();

            return view('latihan.show-latihan', compact('level', 'grade', 'menu', 'subject', 'chapters'));
        } elseif ($menu->slug === 'tryout' || $menu->slug === 'utbk') {
            // Panggil model Exam langsung berdasarkan Mapel
            $exams = Exam::where('subject_id', $subject->id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('tryout.show-tryout', compact('level', 'grade', 'menu', 'subject', 'exams'));
        }

        abort(404, 'Menu tidak ditemukan');
    }

    // 6. Tampilkan Bacaan Materi
    public function showMaterial($level_slug, $grade_slug, $menu_slug, $subject_slug, $material_slug)
    {
        $level = EducationLevel::where('slug', $level_slug)->firstOrFail();
        $grade = Grade::where('slug', $grade_slug)->where('education_level_id', $level->id)->firstOrFail();
        $menu = LevelMenu::where('slug', $menu_slug)->where('education_level_id', $level->id)->firstOrFail();
        $subject = Subject::where('slug', $subject_slug)->where('grade_id', $grade->id)->firstOrFail();

        $material = Material::where('slug', $material_slug)
            ->whereHas('chapter', function ($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            })->firstOrFail();

        return view('materi.detail-materi', compact('level', 'grade', 'menu', 'subject', 'material'));
    }

    // 7. Tampilkan Mengerjakan Latihan
    public function showExercise($level_slug, $grade_slug, $menu_slug, $subject_slug, $exercise_slug)
    {
        $level = EducationLevel::where('slug', $level_slug)->firstOrFail();
        $grade = Grade::where('slug', $grade_slug)->where('education_level_id', $level->id)->firstOrFail();
        $menu = LevelMenu::where('slug', $menu_slug)->where('education_level_id', $level->id)->firstOrFail();
        $subject = Subject::where('slug', $subject_slug)->where('grade_id', $grade->id)->firstOrFail();

        // Tambahkan ->with('chapter') agar database tidak dipanggil berulang kali
        $exercise = Exercise::with('chapter')->where('slug', $exercise_slug)
            ->whereHas('chapter', function ($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            })->firstOrFail();

        // Ekstrak data bab dari relasi exercise
        $chapter = $exercise->chapter;

        // Tambahkan variabel 'chapter' ke dalam compact
        return view('latihan.kerjakan', compact('level', 'grade', 'menu', 'subject', 'exercise', 'chapter'));
    }
}
