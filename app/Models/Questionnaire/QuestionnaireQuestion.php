<?php

namespace App\Models\Questionnaire;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuestion extends Model
{
    use HasFactory;

    protected $table = 'questionnaires_questions';
    protected $fillable = [
        'questionnaire_id',
        'type',
        'text',
        'scale_min',
        'scale_max',
        'label_min',
        'label_max',
        'position',
    ];
}
