<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulQuiz extends Model
{
    use HasFactory;

    protected $table = 'modul_quizzes';
    protected $fillable = ['pertanyaan']; 

    public function courseModul()
    {
        return $this->belongsTo(CourseModul::class);
    }

    public function modulQuizAnswer()
    {
        return $this->hasMany(ModulQuizAnswer::class);
    }

    public function answers()
    {
        return $this->hasMany(ModulQuizAnswer::class, 'modul_quiz_id');
    }
}
