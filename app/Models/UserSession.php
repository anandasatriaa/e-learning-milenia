<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'login_time', 'logout_time', 'session_duration'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
