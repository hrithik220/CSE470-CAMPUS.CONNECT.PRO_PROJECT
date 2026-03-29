<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTutorProfileRequest;
use App\Models\Subject;
use App\Models\TutorProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TutorProfileController extends Controller
{
    public function index(Request $request): View
    {
        $query = TutorProfile::query()
            ->with(['user:id,name,email', 'subjects:id,code,name'])
            ->withCount(['reviews', 'availabilitySlots'])
            ->withAvg('reviews', 'rating')
            ->where('status', 'active');

        if ($request->filled('search')) {
            $term = trim((string) $request->input('search'));

            $query->where(function ($q) use ($term) {
                $q->where('headline', 'like', "%{$term}%")
                    ->orWhere('bio', 'like', "%{$term}%")
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
                    })
                    ->orWhereHas('subjects', function ($subjectQuery) use ($term) {
                        $subjectQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('code', 'like', "%{$term}%");
                    });
            });
        }

        if ($request->filled('subject')) {
            $subjectId = (int) $request->input('subject');
            $query->whereHas('subjects', fn ($subjectQuery) => $subjectQuery->where('subjects.id', $subjectId));
        }

        $tutors = $query->latest()->paginate(9)->withQueryString();
        $subjects = Subject::query()->orderBy('code')->orderBy('name')->get();

        return view('tutors.index', compact('tutors', 'subjects'));
    }

    public function show(TutorProfile $tutorProfile): View
    {
        $tutorProfile->load([
            'user:id,name,email',
            'subjects:id,code,name',
            'availabilitySlots',
            'reviews.student:id,name',
        ]);

        $viewerReview = auth()->check()
            ? $tutorProfile->reviews()->where('student_id', auth()->id())->first()
            : null;

        return view('tutors.show', compact('tutorProfile', 'viewerReview'));
    }

    public function create(): View|RedirectResponse
    {
        $existingProfile = auth()->user()->tutorProfile;

        if ($existingProfile) {
            return redirect()->route('tutors.edit', $existingProfile)
                ->with('status', 'You already have a tutor profile. You can edit it here.');
        }

        $subjects = Subject::query()->orderBy('code')->orderBy('name')->get();

        return view('tutors.create', compact('subjects'));
    }

    public function store(StoreTutorProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $tutorProfile = DB::transaction(function () use ($validated) {
            $profile = TutorProfile::create([
                'user_id' => auth()->id(),
                'headline' => $validated['headline'] ?? null,
                'bio' => $validated['bio'],
                'is_free' => (bool) $validated['is_free'],
                'hourly_rate' => (bool) $validated['is_free'] ? null : $validated['hourly_rate'],
                'status' => 'active',
            ]);

            $profile->subjects()->sync($validated['subjects']);
            $profile->availabilitySlots()->createMany($this->normalizeAvailability($validated['availability']));

            return $profile;
        });

        return redirect()->route('tutors.show', $tutorProfile)
            ->with('status', 'Tutor profile created successfully.');
    }

    public function edit(TutorProfile $tutorProfile): View
    {
        abort_unless($tutorProfile->user_id === auth()->id(), 403);

        $tutorProfile->load(['subjects:id,code,name', 'availabilitySlots']);
        $subjects = Subject::query()->orderBy('code')->orderBy('name')->get();

        return view('tutors.edit', compact('tutorProfile', 'subjects'));
    }

    public function update(StoreTutorProfileRequest $request, TutorProfile $tutorProfile): RedirectResponse
    {
        abort_unless($tutorProfile->user_id === auth()->id(), 403);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $tutorProfile) {
            $tutorProfile->update([
                'headline' => $validated['headline'] ?? null,
                'bio' => $validated['bio'],
                'is_free' => (bool) $validated['is_free'],
                'hourly_rate' => (bool) $validated['is_free'] ? null : $validated['hourly_rate'],
            ]);

            $tutorProfile->subjects()->sync($validated['subjects']);
            $tutorProfile->availabilitySlots()->delete();
            $tutorProfile->availabilitySlots()->createMany($this->normalizeAvailability($validated['availability']));
        });

        return redirect()->route('tutors.show', $tutorProfile)
            ->with('status', 'Tutor profile updated successfully.');
    }

    private function normalizeAvailability(array $availability): array
    {
        return collect($availability)
            ->map(fn (array $slot) => [
                'day_of_week' => (int) $slot['day_of_week'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
            ])
            ->sortBy(fn (array $slot) => sprintf('%d-%s', $slot['day_of_week'], $slot['start_time']))
            ->values()
            ->all();
    }
}
