<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

 // <-- 1. PASTIKAN TAMBAHKAN BARIS INI

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

    // Menampilkan halaman pengaturan urutan materi
    public function sortIndex($chapter_id)
    {
        $chapter = Chapter::with(['materials' => function ($q) {
            $q->orderBy('order_num', 'asc');
        }])->findOrFail($chapter_id);

        return view('admin.courses.material_sort', compact('chapter'));
    }

    // Menerima data urutan baru via AJAX (JSON)
    public function reorder(Request $request, $chapter_id)
    {
        $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'exists:materials,id',
        ]);

        foreach ($request->ordered_ids as $index => $id) {
            Material::where('id', $id)
                ->where('chapter_id', $chapter_id)
                ->update(['order_num' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil disimpan',
        ]);
    }
}
