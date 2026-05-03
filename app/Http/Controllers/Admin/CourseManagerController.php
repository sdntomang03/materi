<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\EducationLevel;
use App\Models\Grade;
use App\Models\LevelMenu;
use App\Models\Subject;

class CourseManagerController extends Controller
{
    public function index()
    {
        $levels = EducationLevel::withCount('grades')->get();

        return view('admin.courses.levels', compact('levels'));
    }

    public function showLevel($level_id)
    {
        $level = EducationLevel::findOrFail($level_id);
        $grades = Grade::where('education_level_id', $level->id)->orderBy('order_num')->get();

        // Method ini sekarang fokus menampilkan Kelas saja
        return view('admin.courses.grades', compact('level', 'grades'));
    }

    /**
     * BARU: TAMPILKAN MENU BERDASARKAN KELAS YANG DIPILIH
     * URL: /admin/courses/grade/{grade_id}/menus
     */
    public function showGradeMenus($grade_id)
    {
        // 1. Cari data Kelasnya
        $grade = Grade::with('educationLevel')->findOrFail($grade_id);

        // 2. Ambil Menu berdasarkan education_level_id milik kelas tersebut
        // Pastikan di database tabel 'level_menus' sudah ada data dengan education_level_id yang cocok
        $menus = LevelMenu::where('education_level_id', $grade->education_level_id)
            ->orderBy('order_num')
            ->get();

        return view('admin.courses.grade_menus', compact('grade', 'menus'));
    }

    /**
     * UPDATE: TAMPILKAN MAPEL BERDASARKAN KELAS & MENU
     * URL: /admin/courses/grade/{grade_id}/menu/{menu_id}/subjects
     */
    public function showSubjects($grade_id, $menu_id)
    {
        $grade = Grade::with('educationLevel')->findOrFail($grade_id);
        $menu = LevelMenu::findOrFail($menu_id);

        $subjects = Subject::where('grade_id', $grade->id)->get();

        return view('admin.courses.subjects', compact('grade', 'menu', 'subjects'));
    }

    // showSubject dan showChapter tetap sama, namun pastikan
    // view-nya membawa variabel menu_id agar tombol "Kembali" tidak error.
    public function showSubject($grade_id, $menu_id, $subject_id)
    {
        $grade = Grade::findOrFail($grade_id);
        $menu = LevelMenu::findOrFail($menu_id);
        $subject = Subject::findOrFail($subject_id);

        $chapters = Chapter::where('subject_id', $subject->id)
            ->withCount(['materials', 'exercises'])
            ->orderBy('order_num')
            ->get();

        return view('admin.courses.chapters', compact('grade', 'menu', 'subject', 'chapters'));
    }

    public function showChapter($menu_id, $chapter_id)
    {
        // Cari menu saat ini
        $menu = LevelMenu::findOrFail($menu_id);

        // Cari Bab, sekaligus tarik data Relasi ke Atas (Subject, Grade)
        // dan Relasi ke Bawah (Materials, Exercises)
        $chapter = Chapter::with(['subject.grade', 'materials', 'exercises'])->findOrFail($chapter_id);

        // Ekstrak data untuk memudahkan di Blade
        $subject = $chapter->subject;
        $grade = $subject->grade;

        return view('admin.courses.chapter_content', compact('menu', 'chapter', 'subject', 'grade'));
    }
}
