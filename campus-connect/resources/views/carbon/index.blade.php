{{-- resources/views/carbon/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Carbon Footprint — Campus Connect Pro')

@push('styles')
<style>
    .eco-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 22px 26px;
    }
    .vehicle-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        padding: 12px 10px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: all .15s;
        background: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        color: #4b5563;
    }
    .vehicle-btn:hover { border-color: #6366f1; color: #6366f1; }
    .vehicle-btn.selected { border-color: #6366f1; background: #eef2ff; color: #4338ca; }
    .vehicle-btn .emoji { font-size: 1.6rem; line-height: 1; }

    .result-box {
        border-radius: 12px;
        padding: 18px 22px;
        display: none;
    }
    .result-box.show { display: block; }

    .stat-chip {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 16px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🌿 Carbon Footprint Calculator</h1>
            <p class="text-sm text-gray-500 mt-1">Track the environmental impact of your campus rides</p>
        </div>
    </div>

    {{-- Personal stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="stat-chip">
            <div class="text-2xl font-black text-green-600">{{ number_format($stats->total_saved ?? 0, 2) }}</div>
            <div class="text-xs text-gray-400 mt-0.5">kg CO₂ Saved</div>
        </div>
        <div class="stat-chip">
            <div class="text-2xl font-black text-red-400">{{ number_format($stats->total_emitted ?? 0, 2) }}</div>
            <div class="text-xs text-gray-400 mt-0.5">kg CO₂ Emitted</div>
        </div>
        <div class="stat-chip">
            <div class="text-2xl font-black text-blue-600">{{ number_format($stats->total_distance ?? 0, 1) }}</div>
            <div class="text-xs text-gray-400 mt-0.5">km Traveled</div>
        </div>
        <div class="stat-chip">
            <div class="text-2xl font-black text-indigo-600">{{ number_format($stats->total_trips ?? 0) }}</div>
            <div class="text-xs text-gray-400 mt-0.5">Trips Logged</div>
        </div>
    </div>

    {{-- Campus impact bar --}}
    <div class="eco-card mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-green-100">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🌍</span>
            <div>
                <div class="font-bold text-gray-800">Campus Community Impact</div>
                <div class="text-sm text-gray-600">
                    Together we've saved
                    <strong class="text-green-700">{{ number_format($campusStats->campus_saved ?? 0, 2) }} kg CO₂</strong>
                    across <strong>{{ number_format($campusStats->campus_trips ?? 0) }}</strong> shared trips —
                    equivalent to planting
                    <strong class="text-green-700">{{ number_format(($campusStats->campus_saved ?? 0) / 21, 1) }} trees</strong>!
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Calculator form --}}
        <div class="eco-card">
            <h2 class="font-semibold text-gray-800 mb-4">Log a Trip</h2>

            @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                ✅ {{ session('success') }}
            </div>
            @endif

            <form id="carbonForm" method="POST" action="{{ route('carbon.store') }}">
                @csrf

                {{-- Vehicle type --}}
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    Vehicle Type
                </label>
                <div class="grid grid-cols-3 gap-2 mb-4" id="vehicleGrid">
                    @php
                        $vehicles = [
                            'car'        => ['🚗', 'Car'],
                            'motorcycle' => ['🏍️', 'Motorcycle'],
                            'cng'        => ['🛺', 'CNG / Auto'],
                            'bus'        => ['🚌', 'Bus'],
                            'bicycle'    => ['🚲', 'Bicycle'],
                            'walking'    => ['🚶', 'Walking'],
                        ];
                    @endphp
                    @foreach($vehicles as $val => [$emoji, $label])
                    <button type="button"
                            class="vehicle-btn {{ old('vehicle_type') == $val ? 'selected' : '' }}"
                            data-value="{{ $val }}"
                            onclick="selectVehicle('{{ $val }}', this)">
                        <span class="emoji">{{ $emoji }}</span>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="vehicle_type" id="vehicleInput" value="{{ old('vehicle_type', '') }}">
                @error('vehicle_type')
                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                @enderror

                {{-- Distance --}}
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                    Distance (km)
                </label>
                <input type="number" name="distance_km" id="distanceInput"
                       value="{{ old('distance_km') }}"
                       min="0.1" max="500" step="0.1" placeholder="e.g. 5.5"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 mb-4"
                       oninput="livePreview()">
                @error('distance_km')
                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                @enderror

                {{-- Passengers --}}
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                    Total Passengers (incl. driver)
                </label>
                <input type="number" name="passengers" id="passengersInput"
                       value="{{ old('passengers', 1) }}"
                       min="1" max="10" placeholder="1"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 mb-4"
                       oninput="livePreview()">

                {{-- Shared ride checkbox --}}
                <label class="flex items-center gap-2 text-sm text-gray-600 mb-4 cursor-pointer">
                    <input type="checkbox" name="is_shared_ride" value="1"
                           {{ old('is_shared_ride') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600">
                    This was a shared / carpool ride
                </label>

                {{-- Live preview result --}}
                <div id="resultBox" class="result-box bg-green-50 border border-green-100 mb-4">
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div>
                            <div class="text-xl font-black text-green-600" id="savedVal">—</div>
                            <div class="text-xs text-gray-500">kg CO₂ Saved</div>
                        </div>
                        <div>
                            <div class="text-xl font-black text-red-400" id="emittedVal">—</div>
                            <div class="text-xs text-gray-500">kg CO₂ Emitted</div>
                        </div>
                    </div>
                    <div class="text-xs text-center text-gray-500 mt-2" id="treesVal"></div>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    🌿 Log This Trip
                </button>
            </form>
        </div>

        {{-- Trip history --}}
        <div class="eco-card">
            <h2 class="font-semibold text-gray-800 mb-4">Your Trip History</h2>

            @if($logs->isEmpty())
                <p class="text-center text-gray-400 py-8 text-sm">No trips logged yet. Use the calculator!</p>
            @else
            <div class="space-y-2 max-h-[480px] overflow-y-auto pr-1">
                @php
                    $vEmoji = ['car'=>'🚗','motorcycle'=>'🏍️','cng'=>'🛺','bus'=>'🚌','bicycle'=>'🚲','walking'=>'🚶'];
                @endphp
                @foreach($logs as $log)
                <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                    <div class="text-2xl">{{ $vEmoji[$log->vehicle_type] ?? '🚗' }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-700 capitalize">
                            {{ $log->vehicle_type }} · {{ $log->distance_km }} km
                            @if($log->is_shared_ride)
                                <span class="text-xs text-indigo-500 ml-1">shared</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400">{{ $log->created_at->format('d M, H:i') }} · {{ $log->passengers }} pax</div>
                    </div>
                    <div class="text-right text-xs">
                        <div class="text-green-600 font-bold">−{{ $log->co2_saved_kg }} kg</div>
                        <div class="text-gray-400">saved</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let debounceTimer;

function selectVehicle(value, el) {
    document.querySelectorAll('.vehicle-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('vehicleInput').value = value;
    livePreview();
}

function livePreview() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        const vehicle  = document.getElementById('vehicleInput').value;
        const distance = document.getElementById('distanceInput').value;
        const pax      = document.getElementById('passengersInput').value;

        if (!vehicle || !distance || parseFloat(distance) <= 0) return;

        try {
            const res  = await fetch('{{ route("carbon.preview") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ vehicle_type: vehicle, distance_km: distance, passengers: pax })
            });
            const data = await res.json();
            document.getElementById('savedVal').textContent   = data.saved + ' kg';
            document.getElementById('emittedVal').textContent = data.emitted + ' kg';
            document.getElementById('treesVal').textContent   =
                data.trees_equivalent > 0
                    ? `🌳 Equivalent to ${data.trees_equivalent} tree-days of CO₂ absorption`
                    : '';
            document.getElementById('resultBox').classList.add('show');
        } catch(e) { /* silent */ }
    }, 400);
}
</script>
@endpush
