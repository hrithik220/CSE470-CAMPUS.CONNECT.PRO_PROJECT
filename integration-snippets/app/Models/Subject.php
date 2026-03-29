<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function tutorProfiles(): BelongsToMany
    {
        return $this->belongsToMany(TutorProfile::class)->withTimestamps();
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->code ? "{$this->code} - {$this->name}" : $this->name;
    }
}
