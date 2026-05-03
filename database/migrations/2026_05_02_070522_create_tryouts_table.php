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
        Schema::create('tryouts', function (Blueprint $table) {
            $table->id();
            // Relasi langsung ke Tingkat (karena Tryout biasanya gabungan beberapa mapel)
            $table->foreignId('education_level_id')->constrained()->cascadeOnDelete();

            $table->string('title'); // Contoh: Try Out USBN SD 2026
            $table->string('slug')->unique();
            $table->dateTime('start_datetime')->nullable(); // Kapan ujian dibuka
            $table->dateTime('end_datetime')->nullable(); // Kapan ujian ditutup
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryouts');
    }
};
