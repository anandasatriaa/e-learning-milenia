<?php

namespace App\Models\Course;

use App\Models\Category\SubCategory;
use App\Models\Category\Category;
use App\Models\Category\DivisiCategory;
use App\Models\Category\LearningCategory;
use App\Models\User;
use App\Models\Nilai\Nilai;
use App\Models\Nilai\NilaiMatriks;
use App\Models\UserCourseEnroll;
use App\Models\Questionnaire\Questionnaire;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $appends = ['thumbnail_url', 'thumbnail_video_url'];
    protected $table = 'courses';
    protected $fillable = [
        'category_id',
        'divisi_category_id',
        'learning_cat_id',
        'sub_category_id',
        'nama_kelas',
        'thumbnail',
        'thumbnail_video',
        'deskripsi',
        'active',
        'time_spend'
    ];

    public function getThumbnailUrlAttribute()
    {
        return empty($this->thumbnail) ? asset('img/no_image.jpg') : asset('/storage/course/thumbnail/' . $this->thumbnail);
    }

    public function getThumbnailVideoUrlAttribute()
    {
        return empty($this->thumbnail_video) ? asset('img/no_image.jpg') : asset('/storage/course/thumbnail_video/' . $this->thumbnail_video);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function divisiCategory()
    {
        return $this->belongsTo(DivisiCategory::class);
    }

    public function learningCategory()
    {
        return $this->belongsTo(LearningCategory::class, 'learning_cat_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_course_enrolls', 'course_id', 'user_id');
    }

    public function modul()
    {
        return $this->hasMany(CourseModul::class, 'course_id', 'id')
            ->where('active', 1)
            ->orderBy('no_urut', 'asc');
    }

    public function userCourseEnroll()
    {
        return $this->hasMany(UserCourseEnroll::class, 'course_id');
    }

    public function nilai()
    {
        return $this->hasManyThrough(Nilai::class, UserCourseEnroll::class);
    }

    public function nilaimatriks()
    {
        return $this->hasManyThrough(NilaiMatriks::class, UserCourseEnroll::class);
    }

    public function moduls()
    {
        return $this->hasMany(CourseModul::class, 'course_id');
    }

    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class, 'questionnaires_courses');
    }
}
