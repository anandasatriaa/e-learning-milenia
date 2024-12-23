<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulQuizUserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'modul_quizzes_id',
        'jawaban',
        'kode_jawaban'
    ];

    public function modulQuiz()
    {
        return $this->belongsTo(ModulQuiz::class, 'modul_quizzes_id');
    }
}
