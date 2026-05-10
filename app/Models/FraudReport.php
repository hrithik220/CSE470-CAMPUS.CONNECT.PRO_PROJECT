<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_user_id',
        'reported_by',
        'type',
        'reason',
        'status',
        'admin_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public const TYPES = [
        'fake_review' => 'Fake Review',
        'spam_listing' => 'Spam Listing',
        'karma_manipulation' => 'Karma Manipulation',
        'suspicious_activity' => 'Suspicious Activity',
        'other' => 'Other',
    ];

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnresolved($query)
    {
        return $query->whereIn('status', ['pending', 'investigating']);
    }

    public function resolve(User $admin, string $notes, string $status = 'resolved'): void
    {
        $this->update([
            'status' => $status,
            'admin_notes' => $notes,
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
