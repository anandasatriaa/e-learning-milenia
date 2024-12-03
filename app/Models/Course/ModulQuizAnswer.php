<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulQuizAnswer extends Model
{
    use HasFactory;

    public function modulQuiz()
    {
        return $this->belongsTo(ModulQuiz::class);
    }
}
