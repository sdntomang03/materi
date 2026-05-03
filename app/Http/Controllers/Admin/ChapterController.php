<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Menyimpan Bab Baru ke database
     */
    public function store(Request $request, $subject_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_num' => 'required|integer',
        ]);

        Chapter::create([
            'subject_id' => $subject_id,
            'name' => $request->name,
            'order_num' => $request->order_num,
        ]);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Bab baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data Bab
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_num' => 'required|integer',
        ]);

        $chapter = Chapter::findOrFail($id);
        $chapter->update([
            'name' => $request->name,
            'order_num' => $request->order_num,
        ]);

        return redirect()->back()->with('success', 'Data Bab berhasil diperbarui!');
    }

    /**
     * Menghapus Bab beserta isinya
     */
    public function destroy(Request $request, $id)
    {
        // Tarik data bab beserta relasi mapelnya
        $chapter = Chapter::with('subject')->findOrFail($id);

        // Simpan variabel untuk keperluan redirect setelah dihapus
        $grade_id = $chapter->subject->grade_id;
        $subject_id = $chapter->subject_id;

        // Kita tangkap menu_id dari form agar bisa kembali ke menu yang tepat
        $menu_id = $request->menu_id;

        // Hapus bab dari database
        $chapter->delete();

        // Redirect secara manual ke daftar bab (bukan back() karena halaman ini sudah musnah)
        return redirect()->route('admin.courses.subject.detail', [
            'grade_id' => $grade_id,
            'menu_id' => $menu_id,
            'subject_id' => $subject_id,
        ])->with('success', 'Bab beserta isinya berhasil dihapus!');
    }
}
