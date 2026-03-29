<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'is_free',
        'hourly_rate',
        'status',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'hourly_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withTimestamps()->orderBy('code')->orderBy('name');
    }

    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(TutorAvailabilitySlot::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(TutorReview::class)->latest();
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function getRatingsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getDisplayRateAttribute(): string
    {
        return $this->is_free
            ? 'Free'
            : 'BDT ' . number_format((float) $this->hourly_rate, 2) . '/hour';
    }

    public function getGroupedAvailabilityAttribute()
    {
        return $this->availabilitySlots->groupBy('day_of_week');
    }
}
