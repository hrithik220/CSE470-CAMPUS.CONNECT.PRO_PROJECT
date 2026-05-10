<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapsService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_maps.api_key', '');
    }

    /**
     * Geocode an address to lat/lng coordinates.
     */
    public function geocode(string $address): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('Maps Service: Google Maps API key not configured.');
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $this->apiKey,
            ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $location = $response->json('results.0.geometry.location');
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                    'formatted_address' => $response->json('results.0.formatted_address'),
                ];
            }

            Log::warning('Geocoding failed', ['address' => $address, 'status' => $response->json('status')]);
            return null;

        } catch (\Exception $e) {
            Log::error('Maps Service Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Calculate distance between two points in kilometers.
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Get directions URL for Google Maps.
     */
    public function getDirectionsUrl(string $origin, string $destination): string
    {
        return 'https://www.google.com/maps/dir/' .
            urlencode($origin) . '/' . urlencode($destination);
    }

    /**
     * Get static map image URL.
     */
    public function getStaticMapUrl(float $lat, float $lng, int $zoom = 15, string $size = '600x300'): string
    {
        return "https://maps.googleapis.com/maps/api/staticmap?" . http_build_query([
            'center' => "{$lat},{$lng}",
            'zoom' => $zoom,
            'size' => $size,
            'markers' => "color:red|{$lat},{$lng}",
            'key' => $this->apiKey,
        ]);
    }

    /**
     * Estimate CO2 saved for a shared ride (kg).
     */
    public function estimateCo2Saved(float $distanceKm, int $passengers): float
    {
        $co2PerKm = config('karma.sustainability.co2_per_km_saved', 0.21);
        // CO2 saved = distance * co2_per_km * (passengers - 1) since those passengers would have driven alone
        return round($distanceKm * $co2PerKm * max(0, $passengers - 1), 2);
    }
}
