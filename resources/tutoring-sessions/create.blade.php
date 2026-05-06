<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-6">
            <div class="bg-white shadow rounded-xl p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Book Session</h2>

                <div class="mb-6">
                    <p class="text-lg"><span class="font-semibold">Tutor:</span> {{ $tutor->user->name }}</p>
                    <p><span class="font-semibold">Subjects:</span> {{ $tutor->subjects }}</p>
                    <p><span class="font-semibold">Availability:</span> {{ $tutor->availability }}</p>
                </div>

                <form method="POST" action="/tutoring-sessions/store" class="space-y-4">
                    @csrf

                    <input type="hidden" name="tutor_profile_id" value="{{ $tutor->id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Session Date</label>
                        <input type="date" name="session_date" class="border border-gray-300 rounded-lg p-3 w-full" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Session Time</label>
                        <input type="time" name="session_time" class="border border-gray-300 rounded-lg p-3 w-full" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Location</label>
                        <input type="text" name="meeting_location" class="border border-gray-300 rounded-lg p-3 w-full" placeholder="Library, Room 302" required>
                    </div>

                    <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                        Confirm Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>