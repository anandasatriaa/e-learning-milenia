<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulQuiz extends Model
{
    use HasFactory;

    protected $table = 'modul_quizzes';
    protected $fillable = ['course_modul_id', 'pertanyaan', 'id']; 

    public function courseModul()
    {
        return $this->belongsTo(CourseModul::class, 'course_modul_id');
    }

    public function modulQuizAnswer()
    {
        return $this->hasMany(ModulQuizAnswer::class);
    }

    public function answers()
    {
        return $this->hasMany(ModulQuizAnswer::class, 'modul_quiz_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(ModulQuizUserAnswer::class, 'modul_quizzes_id'); // Sesuaikan nama tabel dan kolom jika berbeda
    }

}
