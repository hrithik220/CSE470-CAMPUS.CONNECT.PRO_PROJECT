<?php

// Paste these methods into your real app/Models/User.php

public function tutorProfile()
{
    return $this->hasOne(\App\Models\TutorProfile::class);
}

public function tutorReviews()
{
    return $this->hasMany(\App\Models\TutorReview::class, 'student_id');
}
