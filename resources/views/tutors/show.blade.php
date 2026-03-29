@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ $tutorProfile->user->name }}</h1>
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-700">
                                {{ $tutorProfile->display_rate }}
                            </span>
                        </div>
                        <p class="mt-2 text-lg text-slate-600">{{ $tutorProfile->headline ?: 'Tutor profile' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Contact: {{ $tutorProfile->user->email }}</p>
                    </div>

                    @auth
                        @if (auth()->id() === $tutorProfile->user_id)
                            <a href="{{ route('tutors.edit', $tutorProfile) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Edit profile
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($tutorProfile->subjects as $subject)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">
                            {{ $subject->display_name }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-6 rounded-2xl bg-slate-50 p-5">
                    <h2 class="text-lg font-semibold text-slate-900">About this tutor</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $tutorProfile->bio }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Ratings summary</h2>
                <div class="mt-4 flex items-end gap-3">
                    <span class="text-4xl font-bold text-slate-900">{{ number_format($tutorProfile->average_rating, 1) }}</span>
                    <span class="pb-1 text-sm text-slate-500">out of 5</span>
                </div>
                <div class="mt-2 text-amber-500 text-xl">
                    @for ($i = 1; $i <= 5; $i++)
                        <span>{{ $i <= round($tutorProfile->average_rating) ? '★' : '☆' }}</span>
                    @endfor
                </div>
                <p class="mt-2 text-sm text-slate-600">{{ $tutorProfile->ratings_count }} student rating(s)</p>

                <div class="mt-6 border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Quick info</h3>
                    <dl class="mt-4 space-y-3 text-sm text-slate-700">
                        <div class="flex items-center justify-between gap-4">
                            <dt>Subjects</dt>
                            <dd class="font-medium">{{ $tutorProfile->subjects->count() }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt>Availability slots</dt>
                            <dd class="font-medium">{{ $tutorProfile->availabilitySlots->count() }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt>Rate</dt>
                            <dd class="font-medium">{{ $tutorProfile->display_rate }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Availability calendar</h2>
                    <p class="mt-1 text-sm text-slate-500">Weekly recurring tutoring availability.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach (\App\Models\TutorAvailabilitySlot::DAYS as $dayValue => $dayLabel)
                    @php($slotsForDay = $tutorProfile->availabilitySlots->where('day_of_week', $dayValue))
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ $dayLabel }}</h3>

                        @if ($slotsForDay->count())
                            <div class="mt-3 space-y-2">
                                @foreach ($slotsForDay as $slot)
                                    <div class="rounded-lg bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700">
                                        {{ $slot->time_range }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-3 text-sm text-slate-400">Unavailable</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Student reviews</h2>
                        <p class="mt-1 text-sm text-slate-500">Feedback from students who used this tutor profile.</p>
                    </div>
                </div>

                @if ($tutorProfile->reviews->count())
                    <div class="mt-6 space-y-4">
                        @foreach ($tutorProfile->reviews as $review)
                            <article class="rounded-xl border border-slate-200 p-5">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h3 class="font-semibold text-slate-900">{{ $review->student->name }}</h3>
                                        <p class="mt-1 text-xs text-slate-500">{{ $review->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-amber-500 text-lg">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                </div>

                                @if ($review->review)
                                    <p class="mt-4 text-sm leading-6 text-slate-700">{{ $review->review }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
                        No student reviews yet.
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900">Leave a rating</h2>
                <p class="mt-1 text-sm text-slate-500">Students can submit or update one rating for this tutor.</p>

                @auth
                    @if (auth()->user()->role === 'student')
                        <form action="{{ route('tutors.reviews.store', $tutorProfile) }}" method="POST" class="mt-6 space-y-4">
                            @csrf
                            <div>
                                <label for="rating" class="mb-1 block text-sm font-medium text-slate-700">Rating</label>
                                <select id="rating" name="rating" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected((int) old('rating', $viewerReview->rating ?? 5) === $i)>
                                            {{ $i }} star{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="review" class="mb-1 block text-sm font-medium text-slate-700">Review</label>
                                <textarea
                                    id="review"
                                    name="review"
                                    rows="5"
                                    maxlength="500"
                                    placeholder="Share what was helpful about this tutor."
                                    class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >{{ old('review', $viewerReview->review ?? '') }}</textarea>
                                @error('review')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                {{ $viewerReview ? 'Update my rating' : 'Submit rating' }}
                            </button>
                        </form>
                    @elseif (auth()->id() === $tutorProfile->user_id)
                        <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            You are viewing your own tutor profile.
                        </div>
                    @else
                        <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            Only students can rate tutor profiles.
                        </div>
                    @endif
                @else
                    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                        Please log in as a student to submit a rating.
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
