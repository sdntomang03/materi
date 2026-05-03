<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Alignment;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager; // Atau gunakan Imagick\Driver
use Intervention\Image\Typography\FontFactory;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $filename = 'soal_'.time().'_'.uniqid().'.webp';

        // Persiapan Folder
        if (! Storage::disk('public')->exists('questions')) {
            Storage::disk('public')->makeDirectory('questions');
        }
        $path = storage_path('app/public/questions/'.$filename);

        try {
            // 2. INSTANTIASI MANAGER (Cara v4)
            // Anda bisa menggunakan static method withDriver()
            $manager = ImageManager::usingDriver(Driver::class);

            // 3. MEMBACA GAMBAR
            // Method read() di v4 menerima FilePath, Binary, atau UploadedFile
            $image = $manager->decodePath($file);
            // 1. Hitung koordinat tengah
            $centerX = $image->width() / 2;
            $centerY = $image->height() / 2;

            $dynamicSize = $image->width() * 0.1;
            $image->scale(height: 300);
            $image->text('Ujian Pro', $centerX, $centerY, function (FontFactory $font) {
                $font->size(80);
                $font->color('fff');
                $font->stroke('ff5500', 1);
                $font->align(Alignment::CENTER, Alignment::CENTER);

            });
            // save encoded image
            $image->save($path);

            return response()->json([
                'success' => true,
                'url' => asset('storage/questions/'.$filename),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengolah gambar: '.$e->getMessage(),
            ], 500);
        }
    }
}
