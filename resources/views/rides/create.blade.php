<form action="{{ route('rides.store') }}" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded shadow-md">
    @csrf
    <h2 class="text-2xl font-bold mb-4">Offer a Ride</h2>
    
    <div class="mb-4">
        <label class="block text-gray-700">Pickup Location</label>
        <input type="text" name="pickup_location" class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700">Destination Area</label>
        <input type="text" name="destination_area" class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700">Departure Time</label>
        <input type="datetime-local" name="departure_time" class="w-full border rounded p-2" required>
    </div>

    <div class="flex gap-4 mb-4">
        <div class="w-1/2">
            <label class="block text-gray-700">Available Seats</label>
            <input type="number" name="available_seats" min="1" class="w-full border rounded p-2" required>
        </div>
        <div class="w-1/2">
            <label class="block text-gray-700">Cost Split (Per Seat)</label>
            <input type="number" name="cost_per_seat" step="0.01" class="w-full border rounded p-2" required>
        </div>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Post Ride</button>
</form>