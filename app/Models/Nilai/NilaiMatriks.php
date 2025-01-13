<?php

namespace App\Models\Nilai;

use App\Models\Course\Course;
use App\Models\Course\CourseModul;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiMatriks extends Model
{
    use HasFactory;

    protected $table = 'nilai_matriks_kompetensi';

    protected $fillable = [
        'user_id',
        'course_id',
        'nilai_quiz',
        'nilai_essay',
        'nilai_praktek',
        'presentase_kompetensi',
        'komentar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function courseModul()
    {
        return $this->belongsTo(CourseModul::class);
    }
}
