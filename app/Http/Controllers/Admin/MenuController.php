<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelMenu; // <-- Pastikan nama Modelnya sesuai!
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    // CREATE (Simpan Menu Baru berdasarkan Level Pendidikan)
    public function store(Request $request, $education_level_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color_theme' => 'nullable|string|max:50',
            'order_num' => 'nullable|integer',
        ]);

        LevelMenu::create([
            'education_level_id' => $education_level_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.rand(100, 999),
            'description' => $request->description,
            'icon' => $request->icon ?? 'fas fa-star', // Default dari migrasi
            'color_theme' => $request->color_theme ?? 'blue', // Default dari migrasi
            'order_num' => $request->order_num ?? 0,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    // UPDATE (Edit Menu)
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color_theme' => 'nullable|string|max:50',
            'order_num' => 'nullable|integer',
        ]);

        $menu = LevelMenu::findOrFail($id);
        $menu->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.rand(100, 999),
            'description' => $request->description,
            'icon' => $request->icon ?? 'fas fa-star',
            'color_theme' => $request->color_theme ?? 'blue',
            'order_num' => $request->order_num ?? 0,
        ]);

        return redirect()->back()->with('success', 'Data Menu berhasil diperbarui!');
    }

    // DELETE (Hapus Menu)
    public function destroy($id)
    {
        $menu = LevelMenu::findOrFail($id);
        $menu->delete();

        return redirect()->back()->with('success', 'Menu beserta semua isinya berhasil dihapus!');
    }
}
