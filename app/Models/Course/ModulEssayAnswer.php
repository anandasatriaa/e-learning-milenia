<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulEssayAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_modul_id',
        'user_id',
        'jawaban'
    ];
}
