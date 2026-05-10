@extends('layouts.app')

@section('title', 'Ride Sharing')
@section('page_title', 'Ride Sharing')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Ride Sharing</h1>
            <p class="text-gray-400">Find campus rides and send join requests.</p>
        </div>

        <div class="flex gap-3">
            <a href="/rides/create" class="bg-indigo-600 hover:bg-indigo-500 px-5 py-3 rounded-xl font-semibold">
                Offer Ride
            </a>

            <a href="/ride-notifications" class="bg-green-600 hover:bg-green-500 px-5 py-3 rounded-xl font-semibold">
                Ride Notifications
            </a>

            <a href="/ride-history" class="bg-purple-600 hover:bg-purple-500 px-5 py-3 rounded-xl font-semibold">
                Ride History
            </a>
        </div>
    </div>

    <div class="glass rounded-2xl p-5">
        <form method="GET" action="/rides" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="destination" placeholder="Destination Area"
                   value="{{ request('destination') }}"
                   class="w-full">

            <input type="date" name="date"
                   value="{{ request('date') }}"
                   class="w-full">

            <input type="number" name="seats" placeholder="Min Seats"
                   value="{{ request('seats') }}"
                   class="w-full">

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 px-6 py-3 rounded-xl font-semibold text-white">
                Search
            </button>
        </form>
    </div>

    <div class="space-y-5">
        @forelse($rides as $ride)
            <div class="glass rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-2">
                    {{ $ride->pickup_location }} → {{ $ride->destination_area }}
                </h2>

                <p class="text-gray-300">
                    <strong>Time:</strong> {{ $ride->departure_time }}
                </p>

                <p class="text-gray-300">
                    <strong>Seats:</strong> {{ $ride->available_seats }}
                    |
                    <strong>Cost:</strong> ৳{{ $ride->cost_per_seat }}
                </p>

                <p class="text-gray-400 mb-4">
                    <strong>Owner:</strong>
                    @if($ride->user_id == auth()->id())
                        {{ auth()->user()->name }}
                    @else
                        {{ $ride->user->name ?? 'Unknown' }}
                    @endif
                </p>

                @if($ride->user_id == auth()->id())
                    <span class="inline-block px-4 py-2 rounded-xl bg-gray-500/10 text-gray-400 border border-white/10">
                        Your Ride
                    </span>
                @else
                    <form method="POST" action="{{ route('rides.join-request', $ride->id) }}">
                        @csrf
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-500 text-white px-5 py-3 rounded-xl font-semibold">
                            Send Join Request
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-400">No rides available.</p>
        @endforelse
    </div>

</div>
@endsection