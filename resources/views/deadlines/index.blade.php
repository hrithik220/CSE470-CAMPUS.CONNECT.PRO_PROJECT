@extends('layouts.app')

@section('title', 'Deadline Tracker')
@section('page_title', 'Deadline Tracker')

@section('content')
<div class="space-y-6">

    <div class="glass rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Add New Deadline</h2>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-red-300">
                <h3 class="font-semibold mb-2">Please fix these errors:</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('deadlines.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Course Code</label>
                    <input type="text" name="course_code" value="{{ old('course_code') }}"
                           placeholder="Example: CSE423"
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="Assignment / Exam title"
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
                    <select name="type"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">
                        <option value="Assignment">Assignment</option>
                        <option value="Exam">Exam</option>
                        <option value="Quiz">Quiz</option>
                        <option value="Presentation">Presentation</option>
                        <option value="Project">Project</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Priority</label>
                    <select name="priority"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deadline Date</label>
                    <input type="date" name="deadline_date" value="{{ old('deadline_date') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deadline Time</label>
                    <input type="time" name="deadline_time" value="{{ old('deadline_time') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="3"
                          placeholder="Write details here..."
                          class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white outline-none">{{ old('description') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-xl font-semibold transition">
                Add Deadline
            </button>
        </form>
    </div>

    <div class="glass rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">My Deadlines</h2>

        @forelse($deadlines as $deadline)
            @php
                $deadlineDateTime = \Carbon\Carbon::parse($deadline->deadline_date . ' ' . $deadline->deadline_time);
                $now = \Carbon\Carbon::now();

                if ($deadline->is_completed) {
                    $statusText = 'Completed';
                    $statusColor = 'text-green-300 bg-green-500/10 border-green-500/20';
                    $reminderText = 'No reminder needed.';
                } elseif ($now->greaterThan($deadlineDateTime)) {
                    $statusText = 'Overdue';
                    $statusColor = 'text-red-300 bg-red-500/10 border-red-500/20';
                    $reminderText = 'Deadline passed.';
                } else {
                    $diff = $now->diff($deadlineDateTime);
                    $statusText = $diff->d . ' days ' . $diff->h . ' hours ' . $diff->i . ' minutes left';
                    $statusColor = 'text-blue-300 bg-blue-500/10 border-blue-500/20';

                    $hoursLeft = $now->diffInHours($deadlineDateTime, false);

                    if ($hoursLeft <= 1) {
                        $reminderText = 'Push Reminder: 1-hour reminder active.';
                    } elseif ($hoursLeft <= 24) {
                        $reminderText = 'Push Reminder: 24-hour reminder active.';
                    } else {
                        $reminderText = 'Push Reminder: Scheduled.';
                    }
                }

                if ($deadline->priority === 'Urgent') {
                    $priorityColor = 'bg-red-500/20 text-red-300';
                } elseif ($deadline->priority === 'High') {
                    $priorityColor = 'bg-orange-500/20 text-orange-300';
                } elseif ($deadline->priority === 'Medium') {
                    $priorityColor = 'bg-yellow-500/20 text-yellow-300';
                } else {
                    $priorityColor = 'bg-green-500/20 text-green-300';
                }
            @endphp

            <div class="p-5 rounded-xl bg-white/5 border border-white/10 mb-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-white">{{ $deadline->title }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs {{ $priorityColor }}">
                                {{ $deadline->priority }}
                            </span>
                        </div>

                        <p class="text-gray-400 text-sm">
                            {{ $deadline->course_code }} • {{ $deadline->type }}
                        </p>

                        <p class="text-gray-300 mt-2">
                            Deadline: {{ $deadline->deadline_date }} at {{ $deadline->deadline_time }}
                        </p>

                        @if($deadline->description)
                            <p class="text-gray-400 mt-2 text-sm">{{ $deadline->description }}</p>
                        @endif

                        <div class="mt-3 px-4 py-2 rounded-lg border {{ $statusColor }}">
                            {{ $statusText }}
                        </div>

                        <div class="mt-2 px-4 py-2 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-300">
                            {{ $reminderText }}
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if(!$deadline->is_completed)
                            <form method="POST" action="{{ route('deadlines.complete', $deadline->id) }}">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white">
                                    Complete
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('deadlines.destroy', $deadline->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this deadline?')"
                                    class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-400">No deadlines added yet.</p>
        @endforelse
    </div>

</div>
@endsection
