<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulEssayAnswer extends Model
{
    use HasFactory;

    protected $table = 'modul_essay_answers';
    protected $fillable = ['course_modul_id', 'user_id', 'jawaban', 'image'];

    public function courseModul()
    {
        return $this->belongsTo(CourseModul::class);
    }
}
