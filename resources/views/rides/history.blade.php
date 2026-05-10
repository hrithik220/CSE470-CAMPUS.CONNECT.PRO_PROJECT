@extends('layouts.app')

@section('title', 'Ride History')
@section('page_title', 'Ride History')

@section('content')
<div class="space-y-6">

    <div class="glass rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Rides You Offered</h2>

        @forelse($offeredRides as $ride)
            <div class="p-5 rounded-xl bg-white/5 border border-white/10 mb-4">
                <h3 class="text-xl font-bold text-white">
                    {{ $ride->pickup_location }} → {{ $ride->destination_area }}
                </h3>

                <p class="text-gray-300 mt-2">
                    <strong>Time:</strong> {{ $ride->departure_time }}
                </p>

                <p class="text-gray-300">
                    <strong>Seats:</strong> {{ $ride->available_seats }}
                    |
                    <strong>Cost:</strong> ৳{{ $ride->cost_per_seat }}
                </p>
            </div>
        @empty
            <p class="text-gray-400">You have not offered any rides yet.</p>
        @endforelse
    </div>

    <div class="glass rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Join Requests You Sent</h2>

        @forelse($joinRequests as $request)
            <div class="p-5 rounded-xl bg-white/5 border border-white/10 mb-4">
                <h3 class="text-xl font-bold text-white">
                    {{ $request->ride->pickup_location ?? 'Unknown' }}
                    →
                    {{ $request->ride->destination_area ?? 'Unknown' }}
                </h3>

                <p class="text-gray-300 mt-2">
                    {{ $request->message }}
                </p>

                <p class="text-sm text-gray-500 mt-2">
                    Sent: {{ $request->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
        @empty
            <p class="text-gray-400">You have not sent any join requests yet.</p>
        @endforelse
    </div>

</div>
@endsection