<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'university_email',
        'password',
        'role',
        'credibility_score',
        'karma_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ── Hrithik: Marketplace relationships ──
    public function marketplaceItems()
    {
        return $this->hasMany(MarketplaceItem::class, 'seller_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    // Reviews received as a seller (F3 & F4)
    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }
}
