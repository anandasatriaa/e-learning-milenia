<?php

namespace App\Models\Course;

use App\Models\Nilai\Nilai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModul extends Model
{
    use HasFactory;

    protected $appends = ['url_media_link'];
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
        return $this->hasMany(ModulQuiz::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
