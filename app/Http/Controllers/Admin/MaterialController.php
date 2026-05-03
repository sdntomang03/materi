<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- 1. PASTIKAN TAMBAHKAN BARIS INI

class MaterialController extends Controller
{
    // CREATE (Simpan)
    public function store(Request $request, $chapter_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Material::create([
            'chapter_id' => $chapter_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title), // <-- 2. TAMBAHKAN PEMBUAT SLUG INI
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Materi baru berhasil ditambahkan.');
    }

    // UPDATE (Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $material = Material::findOrFail($id);
        $material->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title), // <-- 3. TAMBAHKAN DI SINI JUGA
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Materi berhasil diperbarui.');
    }

    // DELETE (Hapus)
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }
}
