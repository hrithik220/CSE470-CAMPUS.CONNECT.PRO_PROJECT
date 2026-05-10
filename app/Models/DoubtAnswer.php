<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoubtAnswer extends Model
{
    protected $fillable = ['doubt_question_id', 'user_id', 'answer', 'votes', 'faculty_verified'];

    public function question()
    {
        return $this->belongsTo(DoubtQuestion::class, 'doubt_question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
