@extends('layouts.app')
@section('title', 'Tutors — Campus Connect Pro')
@section('page_title', 'Tutors')

@section('content')
<div class="fade-in space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">All Tutors</h1>
            <p class="text-gray-400 text-sm">Find senior students and book tutoring sessions.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tutors.create') }}" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-medium">Add New Tutor</a>
            <a href="{{ route('tutoring-sessions.index') }}" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-medium">View Sessions</a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        @forelse($tutors as $tutor)
            <div class="glass rounded-xl p-5 card-hover">
                <h3 class="text-xl font-bold text-white mb-2">{{ $tutor->user->name }}</h3>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Subjects:</span> {{ $tutor->subjects }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Rate:</span> {{ $tutor->is_free ? 'Free' : '৳' . $tutor->hourly_rate . '/hour' }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Availability:</span> {{ $tutor->availability ?? 'Not given' }}</p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Location:</span> {{ $tutor->meeting_location ?? 'Not given' }}</p>
                @if($tutor->bio)
                    <p class="text-gray-400 mt-2 text-sm">{{ $tutor->bio }}</p>
                @endif
                <a href="{{ route('tutoring-sessions.create', $tutor->id) }}" class="inline-block mt-4 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium">Book Session</a>
            </div>
        @empty
            <div class="glass rounded-xl p-6 text-gray-400">No tutor profiles found.</div>
        @endforelse
    </div>
</div>
@endsection
