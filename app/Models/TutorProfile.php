<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'subjects',
        'hourly_rate',
        'is_free',
        'bio',
        'availability',
        'meeting_location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tutoringSessions()
    {
    return $this->hasMany(\App\Models\TutoringSession::class);
    }
}