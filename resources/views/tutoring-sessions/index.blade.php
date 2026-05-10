@extends('layouts.app')
@section('title', 'Tutoring Sessions — Campus Connect Pro')
@section('page_title', 'Tutoring Sessions')

@section('content')
<div class="fade-in space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Tutoring Sessions</h1>
            <p class="text-gray-400 text-sm">Manage booked sessions, reminder countdowns, and meeting maps.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tutors.index') }}" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-medium">View Tutors</a>
            <a href="{{ route('tutors.create') }}" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium">Add New Tutor</a>
        </div>
    </div>

    <div class="grid gap-5">
        @forelse($sessions as $session)
            @php
                $sessionDateTime = \Carbon\Carbon::parse($session->session_date . ' ' . $session->session_time);
                $now = \Carbon\Carbon::now();
                if ($now->lessThan($sessionDateTime)) {
                    $diff = $now->diff($sessionDateTime);
                    $timeRemaining = $diff->d . ' days ' . $diff->h . ' hours ' . $diff->i . ' minutes';
                } else {
                    $timeRemaining = 'Session started or completed';
                }
            @endphp
            <div class="glass rounded-xl p-5 card-hover">
                <h3 class="text-xl font-bold text-white mb-3">{{ $session->tutorProfile->user->name }}</h3>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Student:</span> {{ $session->student->name }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Date:</span> {{ $session->session_date }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Time:</span> {{ $session->session_time }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Location:</span> {{ $session->meeting_location }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Status:</span> {{ ucfirst($session->status) }}</p>
                <div class="mt-4 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-300 px-4 py-3 font-semibold">Time Remaining: {{ $timeRemaining }}</div>
                <iframe width="100%" height="260" class="mt-4 rounded-lg" style="border:0" loading="lazy" allowfullscreen src="https://www.google.com/maps?q={{ urlencode($session->meeting_location) }}&output=embed"></iframe>
            </div>
        @empty
            <div class="glass rounded-xl p-6 text-gray-400">No tutoring sessions booked yet.</div>
        @endforelse
    </div>
</div>
@endsection
