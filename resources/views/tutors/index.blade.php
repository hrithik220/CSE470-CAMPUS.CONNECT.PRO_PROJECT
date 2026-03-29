@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Tutor profiles</h1>
                <p class="mt-2 text-slate-600">Browse campus tutors by expertise, rates, availability, and student ratings.</p>
            </div>

            @auth
                @if (auth()->user()->role === 'tutor')
                    @php($myTutorProfile = auth()->user()->tutorProfile)
                    <a
                        href="{{ $myTutorProfile ? route('tutors.edit', $myTutorProfile) : route('tutors.create') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        {{ $myTutorProfile ? 'Edit my tutor profile' : 'Create tutor profile' }}
                    </a>
                @endif
            @endauth
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" action="{{ route('tutors.index') }}" class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label for="search" class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Tutor name, subject, course code, expertise"
                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="subject" class="mb-1 block text-sm font-medium text-slate-700">Filter by subject</label>
                    <select
                        id="subject"
                        name="subject"
                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All subjects</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected((string) request('subject') === (string) $subject->id)>
                                {{ $subject->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Apply filters
                    </button>
                    <a href="{{ route('tutors.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        @if ($tutors->count())
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($tutors as $tutor)
                    @php($avgRating = (float) ($tutor->reviews_avg_rating ?? 0))
                    <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">{{ $tutor->user->name }}</h2>
                                <p class="mt-1 text-sm text-slate-500">{{ $tutor->headline ?: 'Tutor profile' }}</p>
                            </div>
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                {{ $tutor->display_rate }}
                            </span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($tutor->subjects as $subject)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                    {{ $subject->display_name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                            <div class="text-amber-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <span>{{ number_format($avgRating, 1) }} / 5</span>
                            <span class="text-slate-400">•</span>
                            <span>{{ $tutor->reviews_count }} ratings</span>
                        </div>

                        <p class="mt-4 text-sm max-h-24 overflow-hidden leading-6 text-slate-600">
                            {{ $tutor->bio }}
                        </p>

                        <div class="mt-5 rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                            <p class="font-medium text-slate-800">Availability slots</p>
                            <p class="mt-1">{{ $tutor->availability_slots_count }} weekly slot(s) listed</p>
                        </div>

                        <div class="mt-6 pt-2">
                            <a href="{{ route('tutors.show', $tutor) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                View full profile
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $tutors->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900">No tutor profiles found</h2>
                <p class="mt-2 text-slate-600">Try changing your filters or create the first tutor profile.</p>
            </div>
        @endif
    </div>
@endsection
