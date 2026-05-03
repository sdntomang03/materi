<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order_num');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
