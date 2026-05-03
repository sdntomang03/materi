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
        // 1. Tabel Kelas (Grades)
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel education_levels (Contoh: Kelas 1 ini milik SD)
            $table->foreignId('education_level_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Contoh: Kelas 1, Kelas 2, Kelas 6
            $table->string('slug'); // Contoh: kelas-1, kelas-2
            $table->integer('order_num')->default(0); // Urutan tampil
            $table->timestamps();
        });

        // 2. PERUBAHAN pada Tabel Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            // UBAH INI: Relasi sekarang ke grades, bukan education_levels
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Contoh: Matematika
            $table->string('slug');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('subjects');
    }
};
