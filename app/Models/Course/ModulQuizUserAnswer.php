<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulQuizUserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_modul_id',
        'user_id',
        'modul_quizzes_id',
        'jawaban',
        'kode_jawaban'
    ];
}
