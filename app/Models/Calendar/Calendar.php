<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Calendar extends Model
{
    use HasFactory;

    protected $table = 'calendar';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'acara',
        'user_id',
        'nama',
        'divisi',
        'start_date',
        'end_date',
        'bg_color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'ID');
    }
}