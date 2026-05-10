@extends('layouts.app')

@section('title', 'Ride Notifications')
@section('page_title', 'Ride Notifications')

@section('content')

<div class="glass rounded-2xl p-6">

    <h2 class="text-2xl font-bold text-white mb-6">
        Ride Join Requests
    </h2>

    @forelse($notifications as $notification)

        <div class="p-5 rounded-xl bg-white/5 border border-white/10 mb-4">

            <h3 class="font-semibold text-white mb-2">
                New Request
            </h3>

            <p class="text-gray-300">
                {{ $notification->message }}
            </p>

            <p class="text-sm text-gray-400 mt-2">
                From: {{ $notification->sender->name ?? 'Unknown' }}
            </p>

            <p class="text-sm text-gray-500">
                {{ $notification->created_at->format('d M Y, h:i A') }}
            </p>

            <div class="mt-4 p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-300">
                ✔ F18 Notification: Ride owner received join request
            </div>

        </div>

    @empty

        <p class="text-gray-400">
            No notifications yet.
        </p>

    @endforelse

</div>

@endsection