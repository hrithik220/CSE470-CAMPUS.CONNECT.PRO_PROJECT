<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarmaLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points', 'action', 'description', 'reference_id', 'reference_type'];

    protected $casts = [
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function scopePositive($query)
    {
        return $query->where('points', '>', 0);
    }

    public function scopeNegative($query)
    {
        return $query->where('points', '<', 0);
    }

    public function scopeForMonth($query, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
    }

    public function getIsPositiveAttribute()
    {
        return $this->points > 0;
    }
}
