<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorAvailabilitySlot extends Model
{
    use HasFactory;

    public const DAYS = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    protected $fillable = [
        'tutor_profile_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function tutorProfile(): BelongsTo
    {
        return $this->belongsTo(TutorProfile::class);
    }

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Unknown';
    }

    public function getTimeRangeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time)) . ' - ' . date('g:i A', strtotime($this->end_time));
    }
}
