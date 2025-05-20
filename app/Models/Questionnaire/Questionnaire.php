<?php

namespace App\Models\Questionnaire;

use App\Models\Course\Course;
use App\Models\Questionnaire\QuestionnaireQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    protected $table = 'questionnaires';
    protected $fillable = [
        'title',
        'image',
    ];

    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'questionnaire_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'questionnaires_courses');
    }
}
