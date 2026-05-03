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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel chapters
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();

            $table->string('title'); // Judul materi
            $table->string('slug')->unique();
            $table->longText('content')->nullable(); // Tempat menyimpan HTML + Tailwind
            $table->string('meta_description')->nullable()->default(null); // Meta description untuk SEO
            $table->string('meta_keywords')->nullable()->default(null); // Meta keywords untuk SEO

            $table->integer('order_num')->default(0); // Urutan materi dalam bab
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
