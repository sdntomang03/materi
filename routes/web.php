<?php

use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\CourseManagerController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE AUTHENTICATION & PROFILE (Harus di Atas)
|--------------------------------------------------------------------------
*/
// Rute /login, /register, dll dari Breeze/Jetstream
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| RUTE ADMIN (Statis / Spesifik)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/courses', [CourseManagerController::class, 'index'])->name('courses.index');
    Route::get('/courses/level/{level_id}', [CourseManagerController::class, 'showLevel'])->name('courses.level');

    // Alur baru: Kelas -> Menu -> Mapel
    Route::get('/courses/grade/{grade_id}/menus', [CourseManagerController::class, 'showGradeMenus'])->name('courses.grade.menus');
    Route::get('/courses/grade/{grade_id}/menu/{menu_id}/subjects', [CourseManagerController::class, 'showSubjects'])
        ->name('courses.subjects');
    // Detail Bab & Konten
    Route::get('/courses/grade/{grade_id}/menu/{menu_id}/subject/{subject_id}', [CourseManagerController::class, 'showSubject'])
        ->name('courses.subject.detail');
    Route::get('/courses/menu/{menu_id}/chapter/{chapter_id}', [CourseManagerController::class, 'showChapter'])
        ->name('courses.chapter');
    Route::post('/upload-image', [UploadController::class, 'uploadImage'])->name('upload.image');
});

Route::prefix('admin/courses')->name('admin.courses.')->group(function () {

    // --- CRUD BAB (Chapter) ---
    // Create: Butuh subject_id (Mapel) sebagai induk
    Route::post('/subject/{subject_id}/chapter', [ChapterController::class, 'store'])->name('chapter.store');
    // Update/Delete: Hanya butuh chapter_id
    Route::put('/chapter/{chapter_id}', [ChapterController::class, 'update'])->name('chapter.update');
    Route::delete('/chapter/{chapter_id}', [ChapterController::class, 'destroy'])->name('chapter.destroy');

    // --- CRUD MATERI (Material) ---
    // Create: Hanya butuh chapter_id (Bab) sebagai tempat materi disimpan
    Route::get('/chapter/{chapter_id}/material/create', [MaterialController::class, 'create'])->name('material.create');
    Route::post('/chapter/{chapter_id}/material', [MaterialController::class, 'store'])->name('material.store');

    // Update/Delete: Hanya butuh material_id
    Route::get('/material/{material_id}/edit', [MaterialController::class, 'edit'])->name('material.edit');
    Route::put('/material/{material_id}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/material/{material_id}', [MaterialController::class, 'destroy'])->name('material.destroy');

    // --- CRUD MATA PELAJARAN (SUBJECT) ---
    // Simpan Mapel (Butuh ID Kelas/Grade)
    Route::post('/grade/{grade_id}/subject', [SubjectController::class, 'store'])->name('subject.store');

    // Update Mapel
    Route::put('/subject/{subject_id}', [SubjectController::class, 'update'])->name('subject.update');

    // Hapus Mapel
    Route::delete('/subject/{subject_id}', [SubjectController::class, 'destroy'])->name('subject.destroy');

    // Latihan Soal (Exercise)
    Route::post('/chapter/{chapter_id}/exercise', [ExerciseController::class, 'store'])->name('exercise.store');
    Route::put('/exercise/{exercise_id}', [ExerciseController::class, 'update'])->name('exercise.update');
    Route::delete('/exercise/{exercise_id}', [ExerciseController::class, 'destroy'])->name('exercise.destroy');
    Route::post('/level/{education_level_id}/menu', [MenuController::class, 'store'])->name('menu.store');

    Route::put('/menu/{menu_id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu_id}', [MenuController::class, 'destroy'])->name('menu.destroy');
});
/*
|--------------------------------------------------------------------------
| RUTE FRONTEND BELAJAR (Dinamis / Serakah - WAJIB DI PALING BAWAH)
|--------------------------------------------------------------------------
*/
Route::get('/', [BelajarController::class, 'index'])->name('home');

// 1. Pilih Kelas
Route::get('/{level_slug}', [BelajarController::class, 'showGrades'])->name('level.grades');

// 2. Pilih Menu (Materi / Latihan)
Route::get('/{level_slug}/{grade_slug}', [BelajarController::class, 'showMenu'])->name('grade.menu');

// 3. Pilih Mapel
Route::get('/{level_slug}/{grade_slug}/{menu_slug}', [BelajarController::class, 'showSubjects'])->name('subject.index');

// 4. Pilih Bab
Route::get('/{level_slug}/{grade_slug}/{menu_slug}/{subject_slug}', [BelajarController::class, 'showChapters'])->name('subject.show');

// 5. Baca / Kerjakan
Route::get('/{level_slug}/{grade_slug}/{menu_slug}/{subject_slug}/materi/{material_slug}', [BelajarController::class, 'showMaterial'])->name('material.show');
Route::get('/{level_slug}/{grade_slug}/{menu_slug}/{subject_slug}/latihan/{exercise_slug}', [BelajarController::class, 'showExercise'])->name('exercise.show');
