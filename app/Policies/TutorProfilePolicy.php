<?php

namespace App\Policies;

use App\Models\TutorProfile;
use App\Models\User;

class TutorProfilePolicy
{
    public function update(User $user, TutorProfile $profile): bool
    {
        return $user->id === $profile->user_id;
    }
}
