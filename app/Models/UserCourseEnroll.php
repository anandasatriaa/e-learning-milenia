<?php

namespace App\Models;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Nilai\Nilai;
use App\Models\Nilai\NilaiMatriks;

class UserCourseEnroll extends Model
{
    use HasFactory;

    protected $table = 'user_course_enrolls';
    protected $fillable = ['course_id', 'user_id', 'enroll_date', 'finish_date', 'status', 'time_spend', 'progress_bar'];

    protected $casts = [
        'enroll_date' => 'datetime',
        'finish_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'user_id', 'user_id');
    }

    public function nilaiMatriks()
    {
        return $this->hasOne(NilaiMatriks::class, 'course_id', 'course_id')
            ->where('user_id', $this->user_id);
    }
}
