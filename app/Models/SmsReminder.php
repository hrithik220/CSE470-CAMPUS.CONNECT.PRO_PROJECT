<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsReminder extends Model
{
    protected $fillable = [
        'tutoring_session_id', 'user_id', 'message', 'status',
    ];

    public function tutoringSession()
    {
        return $this->belongsTo(TutoringSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
