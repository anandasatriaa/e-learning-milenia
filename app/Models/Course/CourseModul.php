<?php

namespace App\Models\Course;

use App\Models\Nilai\Nilai;
use App\Models\Nilai\NilaiMatriks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModul extends Model
{
    use HasFactory;

    protected $appends = ['url_media_link'];
    protected $table = 'course_moduls';
    protected $fillable = [
        'course_id',
        'nama_modul',
        'deskripsi',
        'tipe_media',
        'url_media',
        'active',
        'no_urut',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getUrlMediaLinkAttribute()
    {
        switch ($this->tipe_media) {
            case 'video':
                return asset('storage/course/modul/video/' . $this->url_media);
                break;

            case 'pdf':
                return asset('storage/course/modul/pdf/' . $this->url_media);
                break;

            case 'link':
                return $this->url_media;
                break;

            default:
                return '';
                break;
        }
    }

    public function modulQuiz()
    {
        return $this->hasMany(ModulQuiz::class,'course_modul_id', 'id');
    }

    public function modulEssay()
    {
        return $this->hasMany(ModulEssay::class,'course_modul_id', 'id');
    }

    // Relasi ke tabel modul_quizzes
    public function quizzes()
    {
        return $this->hasMany(ModulQuiz::class, 'course_modul_id');
    }

    // Relasi ke tabel modul_essay_questions
    public function essays()
    {
        return $this->hasMany(ModulEssay::class, 'course_modul_id');
    }

    public function userQuizAnswers()
    {
        return $this->hasMany(ModulQuizUserAnswer::class, 'modul_quizzes_id', 'id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function nilaimatriks()
    {
        return $this->hasMany(NilaiMatriks::class);
    }
}
