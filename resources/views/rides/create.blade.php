@extends('layouts.app')
@section('title', 'Offer Ride — Campus Connect Pro')
@section('page_title', 'Offer Ride')

@section('content')
<div class="max-w-3xl mx-auto fade-in">
    <div class="glass rounded-xl p-6">
        <h1 class="text-2xl font-bold text-white mb-5">Offer a Ride</h1>
        <form action="{{ route('rides.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-300 mb-1">Pickup Location</label>
                <input type="text" name="pickup_location" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Destination Area</label>
                <input type="text" name="destination_area" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Departure Time</label>
                <input type="datetime-local" name="departure_time" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Available Seats</label>
                    <input type="number" name="available_seats" min="1" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Cost Split Per Seat</label>
                    <input type="number" name="cost_per_seat" step="0.01" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
                </div>
            </div>
            <button class="px-5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold">Post Ride</button>
        </form>
    </div>
</div>
@endsection
