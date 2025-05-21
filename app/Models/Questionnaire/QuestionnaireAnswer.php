<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireAnswer extends Model
{
    use HasFactory;

    protected $table = 'questionnaires_answers';
    protected $fillable = [
        'question_id',
        'response_id',
        'scale_value',
    ];

    public function question()
    {
        return $this->belongsTo(
            \App\Models\Questionnaire\QuestionnaireQuestion::class,
            'question_id'
        );
    }
}
