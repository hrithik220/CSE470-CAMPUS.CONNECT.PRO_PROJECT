@extends('layouts.app')

@section('title', 'Sustainability Impact')
@section('page_title', 'Community Sustainability Impact')

@section('content')
@php
    $totalItems = \App\Models\Item::count();

    $soldItems = \App\Models\Item::where('status', 'sold')->count();

    $itemsExchanged = $soldItems > 0 ? $soldItems : $totalItems;

    $rideModel = null;
    foreach ([
        \App\Models\Ride::class,
        \App\Models\RideOffer::class,
        \App\Models\RideShare::class,
        \App\Models\RidePost::class,
    ] as $model) {
        if (class_exists($model)) {
            $rideModel = $model;
            break;
        }
    }

    $totalRides = $rideModel ? $rideModel::count() : 0;

    $co2Saved = round(($totalRides * 2.5) + ($itemsExchanged * 1.2), 1);

    $labels = ['Rides Shared', 'Items Exchanged', 'CO₂ Saved'];
    $values = [$totalRides, $itemsExchanged, $co2Saved];
@endphp

<div class="space-y-6">

    <div class="glass rounded-2xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Campus Sustainability Dashboard</h2>
                <p class="text-gray-400 text-sm mt-1">
                    Tracks community-wide impact from ride sharing and reused marketplace items.
                </p>
            </div>

            <div class="px-4 py-2 rounded-xl bg-green-500/10 border border-green-500/20 text-green-300">
                Public Impact View
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Rides Shared</p>
                    <h2 class="text-4xl font-bold text-blue-300 mt-2">{{ $totalRides }}</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <i data-lucide="car" class="w-7 h-7 text-blue-300"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                More shared rides means fewer separate trips and lower transport emissions.
            </p>
        </div>

        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Items Exchanged</p>
                    <h2 class="text-4xl font-bold text-purple-300 mt-2">{{ $itemsExchanged }}</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center">
                    <i data-lucide="repeat-2" class="w-7 h-7 text-purple-300"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                Reusing books, electronics, and supplies reduces unnecessary new purchases.
            </p>
        </div>

        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Estimated CO₂ Saved</p>
                    <h2 class="text-4xl font-bold text-green-300 mt-2">{{ $co2Saved }} kg</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-green-500/10 border border-green-500/20 flex items-center justify-center">
                    <i data-lucide="leaf" class="w-7 h-7 text-green-300"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                Estimated using shared rides and reused item activity.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass rounded-2xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Impact Graph</h3>

            <div class="h-80">
                <canvas id="impactChart"></canvas>
            </div>
        </div>

        <div class="glass rounded-2xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Sustainability Summary</h3>

            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                    <h4 class="font-semibold text-blue-300">Ride Sharing Impact</h4>
                    <p class="text-sm text-gray-400 mt-1">
                        Students shared {{ $totalRides }} rides, reducing duplicate trips around campus.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-purple-500/10 border border-purple-500/20">
                    <h4 class="font-semibold text-purple-300">Marketplace Reuse Impact</h4>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ $itemsExchanged }} items were exchanged or listed instead of being newly purchased.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                    <h4 class="font-semibold text-green-300">Estimated CO₂ Reduction</h4>
                    <p class="text-sm text-gray-400 mt-1">
                        The platform helped save approximately {{ $co2Saved }} kg of CO₂.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-6">
        <h3 class="text-xl font-bold text-white mb-4">Why This Matters</h3>

        <p class="text-gray-300 leading-relaxed">
            CampusConnect Pro promotes sustainability by encouraging students to share rides,
            reuse academic materials, exchange products, and reduce waste. This dashboard
            converts those actions into measurable community-wide impact.
        </p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const impactCanvas = document.getElementById('impactChart');

    new Chart(impactCanvas, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Community Impact',
                data: @json($values),
                borderWidth: 1,
                borderRadius: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#e5e7eb'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#cbd5e1'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.08)'
                    }
                },
                y: {
                    ticks: {
                        color: '#cbd5e1'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.08)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection