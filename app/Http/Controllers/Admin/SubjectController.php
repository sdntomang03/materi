<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- 1. PASTIKAN IMPORT INI ADA

class SubjectController extends Controller
{
    // CREATE (Simpan Mapel Baru)
    public function store(Request $request, $grade_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        Subject::create([
            'grade_id' => $grade_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name), // <-- 2. TAMBAHKAN SLUG OTOMATIS
            'icon' => $request->icon ?? 'fas fa-book',
        ]);

        return redirect()->back()->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    // UPDATE (Edit Mapel)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // <-- 3. TAMBAHKAN DI SINI JUGA
            'icon' => $request->icon ?? 'fas fa-book',
        ]);

        return redirect()->back()->with('success', 'Data Mata Pelajaran berhasil diperbarui!');
    }

    // DELETE (Hapus Mapel)
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }
}
