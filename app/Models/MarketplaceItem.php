<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'title',
        'description',
        'price',
        'condition_rating',
        'category',
        'photos',
        'status',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    // ── Relationships ──
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'item_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'item_id');
    }
}
