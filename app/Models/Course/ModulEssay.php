<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulEssay extends Model
{
    use HasFactory;

    protected $table = 'modul_essay_questions';
    protected $fillable = ['course_modul_id', 'pertanyaan'];
}
