<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    protected $fillable = [
        'user_id',
        'course_code',
        'title',
        'type',
        'priority',
        'deadline_date',
        'deadline_time',
        'description',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}