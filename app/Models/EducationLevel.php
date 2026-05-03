<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;

    // Tambahkan baris ini di semua file model:
    protected $guarded = [];

    public function menus()
    {
        return $this->hasMany(LevelMenu::class)->orderBy('order_num');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class)->orderBy('order_num', 'asc');
    }
}
