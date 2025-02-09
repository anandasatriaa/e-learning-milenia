<?php

namespace App\Models;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseEnroll extends Model
{
    use HasFactory;

    protected $table = 'user_course_enrolls';
    protected $fillable = ['course_id', 'user_id', 'enroll_date', 'finish_date', 'status', 'time_spend', 'progress_bar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
