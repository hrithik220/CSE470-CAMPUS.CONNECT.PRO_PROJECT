<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'title',
        'description',
        'price',
        'category',
        'condition',
        'status',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'views_count' => 'integer',
    ];

    public const CATEGORIES = [
        'textbooks' => 'Textbooks',
        'electronics' => 'Electronics',
        'furniture' => 'Furniture',
        'clothing' => 'Clothing',
        'sports' => 'Sports & Outdoors',
        'supplies' => 'School Supplies',
        'tickets' => 'Tickets & Events',
        'other' => 'Other',
    ];

    public const CONDITIONS = [
        'new' => 'New',
        'used' => 'Used - Good',
        'fair' => 'Fair',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class)->orderBy('sort_order');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ── Computed ───────────────────────────────────────────────────

    public function getPrimaryImageAttribute()
    {
        $image = $this->images->first();
        return $image ? asset('storage/' . $image->image_path) : asset('images/no-image.png');
    }

    public function getCategoryLabelAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getConditionLabelAttribute()
    {
        return self::CONDITIONS[$this->condition] ?? $this->condition;
    }

    public function getConditionBadgeColorAttribute()
    {
        return match($this->condition) {
            'new' => 'bg-green-500',
            'used' => 'bg-yellow-500',
            'fair' => 'bg-orange-500',
            default => 'bg-gray-500',
        };
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeByCondition($query, $condition)
    {
        if ($condition) {
            return $query->where('condition', $condition);
        }
        return $query;
    }

    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }
        return $query;
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function markAsSold(): void
    {
        $this->update(['status' => 'sold']);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
