<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('level_menus', function (Blueprint $table) {
            $table->id();
            // Relasi ke education_levels (SD/SMP/SMA)
            $table->foreignId('education_level_id')->constrained()->cascadeOnDelete();

            $table->string('title'); // Contoh: "Materi Pelajaran", "Persiapan UTBK"
            $table->string('slug');  // Contoh: "materi", "utbk"
            $table->string('description')->nullable(); // Penjelasan singkat menu

            // Konfigurasi Tampilan (Ikon dan Warna)
            $table->string('icon')->default('fas fa-star'); // Class FontAwesome
            $table->string('color_theme')->default('blue'); // Contoh: 'blue', 'emerald', 'rose', 'purple'

            $table->integer('order_num')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_menus');
    }
};
