<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'reviewer_id', 'reviewed_user_id', 'item_id', 'rating', 'comment'];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getStarsHtmlAttribute()
    {
        $html = '<div class="flex gap-0.5">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '<i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>';
            } else {
                $html .= '<i data-lucide="star" class="w-3.5 h-3.5 opacity-30"></i>';
            }
        }
        $html .= '</div>';
        return $html;
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->latest()->limit($limit);
    }
}
