<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'buyer_id', 'seller_id', 'amount', 'status', 'notes'];

    protected $casts = [
        'amount' => 'decimal:2',
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

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForMonth($query, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function markCompleted(): void
    {
        $this->update(['status' => 'completed']);
        $this->item->markAsSold();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function hasReview(): bool
    {
        return $this->review()->exists();
    }
}
