<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = [];

    // Mengubah JSON di database menjadi Array secara otomatis
    protected $casts = [
        'links' => 'array',
    ];
}
