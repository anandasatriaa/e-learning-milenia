<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModul extends Model
{
    use HasFactory;

    protected $appends = ['url_media_link'];

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
        return $this->hasMany(ModulQuiz::class);
    }
}
