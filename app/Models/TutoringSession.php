<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringSession extends Model
{
    protected $fillable = [
        'student_id', 'tutor_profile_id', 'session_date', 'session_time', 'meeting_location', 'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class);
    }

    public function smsReminders()
    {
        return $this->hasMany(SmsReminder::class);
    }
}
