<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulEssay extends Model
{
    use HasFactory;

    protected $table = 'modul_essay_questions';
    protected $fillable = ['course_modul_id','pertanyaan', 'image'];

    public function courseModul()
    {
        return $this->belongsTo(CourseModul::class);
    }

    public function modulEssayAnswer()
    {
        return $this->hasMany(ModulEssayAnswer::class, 'course_modul_id', 'course_modul_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(ModulEssayAnswer::class, 'course_modul_id')->where('user_id', auth()->id()); // Sesuaikan nama tabel dan kolom jika berbeda
    }

}
