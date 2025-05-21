<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireResponse extends Model
{
    use HasFactory;

    protected $table = 'questionnaires_responses';
    protected $fillable = [
        'questionnaire_id',
        'user_id',
        'submitted_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(
            \App\Models\Questionnaire\QuestionnaireAnswer::class,
            'response_id'
        );
    }
}
