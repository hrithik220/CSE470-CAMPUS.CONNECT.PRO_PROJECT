<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'color', 'karma_threshold'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'badge_user')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public static function checkAndAward(User $user): array
    {
        $awarded = [];
        $badges = self::all();

        foreach ($badges as $badge) {
            if ($user->karma_points >= $badge->karma_threshold && !$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                $awarded[] = $badge;
            }
        }

        return $awarded;
    }
}
