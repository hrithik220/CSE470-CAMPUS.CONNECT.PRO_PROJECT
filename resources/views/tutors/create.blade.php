<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-6">
            <div class="bg-white shadow rounded-xl p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Create Tutor Profile</h2>

                <form method="POST" action="/tutors/store" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjects</label>
                        <input class="border border-gray-300 rounded-lg p-3 w-full" name="subjects" placeholder="Subjects">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rate</label>
                        <input class="border border-gray-300 rounded-lg p-3 w-full" name="hourly_rate" placeholder="Rate">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea class="border border-gray-300 rounded-lg p-3 w-full" name="bio" placeholder="Bio"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <input class="border border-gray-300 rounded-lg p-3 w-full" name="availability" placeholder="Availability">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input class="border border-gray-300 rounded-lg p-3 w-full" name="meeting_location" placeholder="Location">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_free" id="is_free">
                        <label for="is_free" class="text-gray-700">Free</label>
                    </div>

                    <button class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>