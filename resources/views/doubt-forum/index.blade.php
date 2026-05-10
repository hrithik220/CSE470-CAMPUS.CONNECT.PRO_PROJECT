@extends('layouts.app')
@section('title', 'Doubt Forum — Campus Connect Pro')
@section('page_title', 'Doubt Forum')

@section('content')
<div class="fade-in space-y-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Doubt Forum</h1>
            <p class="text-gray-400 text-sm">Ask course-specific questions anonymously and vote helpful answers.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tutors.index') }}" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white">Tutors</a>
            <a href="{{ route('tutoring-sessions.index') }}" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white">Sessions</a>
        </div>
    </div>

    <div class="glass rounded-xl p-5">
        <h2 class="text-lg font-semibold text-white mb-4">Post a Question</h2>
        <form method="POST" action="{{ route('doubt-forum.question') }}" class="space-y-3">
            @csrf
            <input name="course_code" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Course Code, e.g. CSE470" required>
            <textarea name="question" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" rows="3" placeholder="Write your question" required></textarea>
            <label class="flex items-center gap-2 text-gray-300"><input type="checkbox" name="is_anonymous" checked> Post anonymously</label>
            <button class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Post Question</button>
        </form>
    </div>

    @foreach($questions as $q)
        <div class="glass rounded-xl p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $q->course_code }}</h3>
                    <p class="text-gray-300 mt-2">{{ $q->question }}</p>
                    <p class="text-gray-500 text-sm mt-2">Asked by: {{ $q->is_anonymous ? 'Anonymous Student' : $q->user->name }}</p>
                </div>
            </div>
            <hr class="border-white/10 my-4">
            <h4 class="font-semibold text-gray-200 mb-3">Answers</h4>
            @forelse($q->answers as $a)
                <div class="rounded-lg bg-white/5 border border-white/10 p-4 mb-3">
                    <p class="text-gray-200">{{ $a->answer }}</p>
                    <p class="text-gray-500 text-sm mt-1">Answered by: {{ $a->user->name }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <form method="POST" action="{{ route('doubt-forum.upvote', $a->id) }}">@csrf<button class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white">Upvote</button></form>
                        <span class="text-gray-300">Votes: {{ $a->votes }}</span>
                        <form method="POST" action="{{ route('doubt-forum.downvote', $a->id) }}">@csrf<button class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white">Downvote</button></form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No answers yet.</p>
            @endforelse
            <form method="POST" action="{{ route('doubt-forum.answer', $q->id) }}" class="mt-4">
                @csrf
                <textarea name="answer" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" rows="2" placeholder="Write an answer" required></textarea>
                <button class="mt-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white">Submit Answer</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
