<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTutorReviewRequest;
use App\Models\TutorProfile;
use App\Models\TutorReview;
use Illuminate\Http\RedirectResponse;

class TutorReviewController extends Controller
{
    public function store(StoreTutorReviewRequest $request, TutorProfile $tutorProfile): RedirectResponse
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->role === 'student', 403, 'Only students can submit tutor ratings.');
        abort_if($tutorProfile->user_id === auth()->id(), 403, 'You cannot rate your own tutor profile.');

        TutorReview::updateOrCreate(
            [
                'tutor_profile_id' => $tutorProfile->id,
                'student_id' => auth()->id(),
            ],
            [
                'rating' => (int) $request->validated('rating'),
                'review' => $request->validated('review'),
            ]
        );

        return redirect()->route('tutors.show', $tutorProfile)
            ->with('status', 'Your rating has been saved.');
    }
}
