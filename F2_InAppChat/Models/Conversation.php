<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'buyer_id', 'seller_id', 'last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function unreadMessagesFor(User $user)
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false);
    }

    public function getOtherParticipant(User $user): User
    {
        return $this->buyer_id === $user->id ? $this->seller : $this->buyer;
    }

    public static function findOrCreateForItem(Item $item, User $buyer): self
    {
        return self::firstOrCreate([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $item->seller_id,
        ]);
    }
}
