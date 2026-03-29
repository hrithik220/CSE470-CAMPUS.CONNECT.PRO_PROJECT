@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Edit tutor profile</h1>
                <p class="mt-2 text-slate-600">Keep your profile accurate so students can make better decisions.</p>
            </div>

            <a href="{{ route('tutors.show', $tutorProfile) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to profile
            </a>
        </div>

        <form action="{{ route('tutors.update', $tutorProfile) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            @include('tutors.partials.form', ['subjects' => $subjects, 'tutorProfile' => $tutorProfile])
        </form>
    </div>
@endsection
