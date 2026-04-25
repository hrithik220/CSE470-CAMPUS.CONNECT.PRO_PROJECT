<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarbonFootprintLog extends Model
{
    protected $fillable = [
        'user_id', 'ride_id', 'vehicle_type', 'distance_km',
        'passengers', 'co2_saved_kg', 'co2_emitted_kg', 'is_shared_ride',
    ];

    protected $casts = [
        'is_shared_ride' => 'boolean',
        'distance_km'    => 'float',
        'passengers'     => 'integer',
        'co2_saved_kg'   => 'float',
        'co2_emitted_kg' => 'float',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

    // ── Emission factors (kg CO₂ per km per vehicle) ──────────────────────────
    // Sources: IPCC / Our World in Data averages

    public const EMISSION_FACTORS = [
        'car'        => 0.171,   // average petrol car
        'motorcycle' => 0.113,
        'cng'        => 0.068,   // CNG auto-rickshaw
        'bus'        => 0.089,   // per passenger-km
        'bicycle'    => 0.0,
        'walking'    => 0.0,
    ];

    // Baseline: solo private car
    public const BASELINE_CAR = 0.171;

    /**
     * Calculate CO₂ emitted and CO₂ saved vs a solo car journey.
     *
     * @param string $vehicleType
     * @param float  $distanceKm
     * @param int    $passengers   total occupants (driver + riders)
     * @return array{emitted: float, saved: float}
     */
    public static function calculate(
        string $vehicleType,
        float  $distanceKm,
        int    $passengers = 1
    ): array {
        $factor   = self::EMISSION_FACTORS[$vehicleType] ?? 0.171;
        $total    = $factor * $distanceKm;
        $perPax   = $passengers > 1 ? $total / $passengers : $total;
        $baseline = self::BASELINE_CAR * $distanceKm;   // what one solo car would emit
        $saved    = max(0, $baseline - $perPax);

        return [
            'emitted' => round($perPax, 4),
            'saved'   => round($saved, 4),
        ];
    }
}
