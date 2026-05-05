<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">My Ride History</h2>

    <h3 class="text-xl mt-6 mb-2">Rides I Offered</h3>
    <table class="w-full text-left border-collapse bg-white shadow">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Route</th>
                <th class="p-2 border">Date</th>
                <th class="p-2 border">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offeredRides as $ride)
            <tr>
                <td class="p-2 border">{{ $ride->pickup_location }} -> {{ $ride->destination_area }}</td>
                <td class="p-2 border">{{ $ride->departure_time }}</td>
                <td class="p-2 border font-bold 
                    {{ $ride->status == 'confirmed' ? 'text-green-600' : ($ride->status == 'cancelled' ? 'text-red-600' : 'text-yellow-500') }}">
                    {{ ucfirst($ride->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="text-xl mt-6 mb-2">Rides I Requested</h3>
    <table class="w-full text-left border-collapse mt-4 bg-white shadow">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Route</th>
                <th class="p-2 border">Request Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requestedRides as $request)
            <tr>
                <td class="p-2 border">{{ $request->ride->pickup_location }} -> {{ $request->ride->destination_area }}</td>
                <td class="p-2 border font-bold 
                    {{ $request->status == 'accepted' ? 'text-green-600' : ($request->status == 'rejected' ? 'text-red-600' : 'text-blue-500') }}">
                    {{ ucfirst($request->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>