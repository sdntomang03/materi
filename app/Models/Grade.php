<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Grade ini milik Level apa? (Cth: Kelas 1 milik SD)
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    // Grade ini punya Mapel apa saja? (Cth: Kelas 1 punya Matematika, B. Indo)
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
