<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Tutoring Sessions</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($sessions as $session)
                    <div class="bg-white rounded-xl shadow p-6 border">
                        <h3 class="text-xl font-semibold mb-3">{{ $session->tutorProfile->user->name }}</h3>
                        <p><span class="font-semibold">Student:</span> {{ $session->student->name }}</p>
                        <p><span class="font-semibold">Date:</span> {{ $session->session_date }}</p>
                        <p><span class="font-semibold">Time:</span> {{ $session->session_time }}</p>
                        <p><span class="font-semibold">Location:</span> {{ $session->meeting_location }}</p>
                        <p><span class="font-semibold">Status:</span> {{ $session->status }}</p>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow p-6 border">
                        No tutoring sessions booked yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>