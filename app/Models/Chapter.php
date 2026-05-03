<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke tabel subjects (Milik Mata Pelajaran apa?)
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Relasi ke tabel materials (Punya materi apa saja?)
    public function materials()
    {
        return $this->hasMany(Material::class)->orderBy('order_num', 'asc');
    }

    // Relasi ke tabel exercises (Punya latihan apa saja?)
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
