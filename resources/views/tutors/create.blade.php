@extends('layouts.app')
@section('title', 'Create Tutor Profile — Campus Connect Pro')
@section('page_title', 'Create Tutor Profile')

@section('content')
<div class="max-w-3xl mx-auto fade-in">
    <div class="glass rounded-xl p-6">
        <h1 class="text-2xl font-bold text-white mb-5">Create Tutor Profile</h1>
        <form method="POST" action="{{ route('tutors.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-300 mb-1">Subjects</label>
                <input name="subjects" value="{{ old('subjects') }}" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="CSE101, MAT110, Physics" required>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Hourly Rate</label>
                <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate') }}" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="500">
            </div>
            <label class="flex items-center gap-2 text-gray-300"><input type="checkbox" name="is_free" class="rounded"> I provide tutoring for free</label>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Bio</label>
                <textarea name="bio" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" rows="4" placeholder="Write about your experience">{{ old('bio') }}</textarea>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Availability</label>
                <input name="availability" value="{{ old('availability') }}" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Sat-Mon 3PM-6PM">
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Meeting Location</label>
                <input name="meeting_location" value="{{ old('meeting_location') }}" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Library 2nd Floor / NSU Library">
            </div>
            <button class="px-5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold">Save Tutor Profile</button>
        </form>
    </div>
</div>
@endsection
