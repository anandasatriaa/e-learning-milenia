<?php

namespace App\Models\Course;

use App\Models\Category\SubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $appends = ['thumbnail_url', 'thumbnail_video_url'];

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

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_course_enrolls', 'course_id', 'user_id');
    }

    public function modul()
    {
        return $this->hasMany(CourseModul::class);
    }
}