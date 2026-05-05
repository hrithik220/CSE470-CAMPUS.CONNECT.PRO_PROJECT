<div class="container mx-auto p-4">
    <form method="GET" action="{{ route('rides.index') }}" class="mb-6 flex gap-4 bg-gray-100 p-4 rounded">
        <input type="text" name="area" placeholder="Destination Area" class="border p-2 rounded w-1/3">
        <input type="date" name="time" class="border p-2 rounded w-1/3">
        <input type="number" name="seats" placeholder="Min Seats" class="border p-2 rounded w-1/3">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Search</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($rides as $ride)
            <div class="border p-4 rounded shadow bg-white">
                <h3 class="font-bold text-lg">{{ $ride->pickup_location }} to {{ $ride->destination_area }}</h3>
                <p>Time: {{ $ride->departure_time }}</p>
                <p>Seats: {{ $ride->available_seats }} | Cost: ${{ $ride->cost_per_seat }}</p>
                
                <form action="{{ route('rides.request', $ride->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Send Join Request
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>