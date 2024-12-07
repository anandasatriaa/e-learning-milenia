<?php

namespace App\Models\Nilai;

use App\Models\Course\Course;
use App\Models\Course\CourseModul;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'user_id',
        'course_id',
        'course_modul_id',
        'nilai_quiz',
        'nilai_essay',
        'komentar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseModul()
    {
        return $this->belongsTo(CourseModul::class);
    }
}
