<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'university_id',
        'karma_points',
        'avatar',
        'bio',
        'phone',
        'is_suspended',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_suspended' => 'boolean',
        'karma_points' => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function items()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    public function purchases()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id')
            ->orWhere('seller_id', $this->id);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function karmaLogs()
    {
        return $this->hasMany(KarmaLog::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_user')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function fraudReports()
    {
        return $this->hasMany(FraudReport::class, 'reported_user_id');
    }



    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class);
    }

    public function tutoringSessionsAsStudent()
    {
        return $this->hasMany(TutoringSession::class, 'student_id');
    }

    public function smsReminders()
    {
        return $this->hasMany(SmsReminder::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

    public function rideRequests()
    {
        return $this->hasMany(RideRequest::class);
    }

    public function doubtQuestions()
    {
        return $this->hasMany(DoubtQuestion::class);
    }

    public function doubtAnswers()
    {
        return $this->hasMany(DoubtAnswer::class);
    }

    // ── Computed Attributes ────────────────────────────────────────

    public function getAverageRatingAttribute()
    {
        return $this->reviewsReceived()->avg('rating') ?? 0;
    }

    public function getTotalSalesAttribute()
    {
        return $this->sales()->where('status', 'completed')->count();
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&size=128';
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_suspended', false);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeTopSellers($query, $limit = 10)
    {
        return $query->withCount(['sales' => function ($q) {
            $q->where('status', 'completed');
        }])->orderByDesc('sales_count')->limit($limit);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isSuspended(): bool
    {
        return $this->is_suspended;
    }

    public function addKarma(int $points, string $action, string $description, $reference = null): void
    {
        $this->increment('karma_points', $points);

        $this->karmaLogs()->create([
            'points' => $points,
            'action' => $action,
            'description' => $description,
            'reference_id' => $reference?->id ?? 0,
            'reference_type' => $reference ? get_class($reference) : 'App\\Models\\User',
        ]);
    }

    public function deductKarma(int $points, string $action, string $description, $reference = null): void
    {
        $newPoints = max(0, $this->karma_points - $points);
        $this->update(['karma_points' => $newPoints]);

        $this->karmaLogs()->create([
            'points' => -$points,
            'action' => $action,
            'description' => $description,
            'reference_id' => $reference?->id ?? 0,
            'reference_type' => $reference ? get_class($reference) : 'App\\Models\\User',
        ]);
    }
}
