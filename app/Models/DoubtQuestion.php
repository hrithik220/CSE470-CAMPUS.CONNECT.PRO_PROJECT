<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoubtQuestion extends Model
{
    protected $fillable = [
        'user_id',
        'course_code',
        'question',
        'is_anonymous',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(DoubtAnswer::class);
    }
}