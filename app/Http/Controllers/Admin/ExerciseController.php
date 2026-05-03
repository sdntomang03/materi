<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExerciseController extends Controller
{
    public function store(Request $request, $chapter_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'nullable|json', // Validasi pastikan format JSON benar
        ]);

        Exercise::create([
            'chapter_id' => $chapter_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.rand(100, 999),
            'questions' => $request->questions,
        ]);

        return redirect()->back()->with('success', 'Latihan soal berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'nullable|json',
        ]);

        $exercise = Exercise::findOrFail($id);
        $exercise->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.rand(100, 999), // Update slug juga
            'questions' => $request->questions,
        ]);

        return redirect()->back()->with('success', 'Latihan soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Exercise::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Latihan soal berhasil dihapus.');
    }
}
