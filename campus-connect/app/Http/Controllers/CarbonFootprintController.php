<?php

namespace App\Http\Controllers;

use App\Models\CarbonFootprintLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarbonFootprintController extends Controller
{
    /**
     * Show the calculator + personal history dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        $logs = CarbonFootprintLog::where('user_id', $user->id)
                                  ->latest()
                                  ->paginate(10);

        // Aggregated stats
        $stats = CarbonFootprintLog::where('user_id', $user->id)
                                   ->select(
                                       DB::raw('SUM(co2_saved_kg)   as total_saved'),
                                       DB::raw('SUM(co2_emitted_kg) as total_emitted'),
                                       DB::raw('SUM(distance_km)    as total_distance'),
                                       DB::raw('COUNT(*)            as total_trips')
                                   )->first();

        // Campus-wide totals
        $campusStats = CarbonFootprintLog::select(
            DB::raw('SUM(co2_saved_kg) as campus_saved'),
            DB::raw('COUNT(*)          as campus_trips')
        )->first();

        return view('carbon.index', compact('user', 'logs', 'stats', 'campusStats'));
    }

    /**
     * AJAX: calculate CO₂ without saving (live preview in form).
     */
    public function preview(Request $request)
    {
        $request->validate([
            'vehicle_type' => 'required|in:car,motorcycle,cng,bus,bicycle,walking',
            'distance_km'  => 'required|numeric|min:0.1|max:500',
            'passengers'   => 'required|integer|min:1|max:10',
        ]);

        $result = CarbonFootprintLog::calculate(
            $request->vehicle_type,
            (float) $request->distance_km,
            (int)   $request->passengers
        );

        // Trees equivalent (1 tree absorbs ~21 kg CO₂ / year)
        $result['trees_equivalent'] = round($result['saved'] / 21, 4);

        return response()->json($result);
    }

    /**
     * Save a manually entered trip.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_type'  => 'required|in:car,motorcycle,cng,bus,bicycle,walking',
            'distance_km'   => 'required|numeric|min:0.1|max:500',
            'passengers'    => 'required|integer|min:1|max:10',
            'is_shared_ride'=> 'boolean',
            'ride_id'       => 'nullable|exists:rides,id',
        ]);

        $calc = CarbonFootprintLog::calculate(
            $data['vehicle_type'],
            (float) $data['distance_km'],
            (int)   $data['passengers']
        );

        $log = CarbonFootprintLog::create([
            'user_id'        => Auth::id(),
            'ride_id'        => $data['ride_id'] ?? null,
            'vehicle_type'   => $data['vehicle_type'],
            'distance_km'    => $data['distance_km'],
            'passengers'     => $data['passengers'],
            'co2_saved_kg'   => $calc['saved'],
            'co2_emitted_kg' => $calc['emitted'],
            'is_shared_ride' => $data['is_shared_ride'] ?? false,
        ]);

        // Update user's cumulative CO₂ saved
        Auth::user()->increment('co2_saved_total', $calc['saved']);

        // Award karma for green travel
        if ($calc['saved'] > 0) {
            KarmaController::award(
                Auth::id(),
                'ride_completed_rider',
                'rides',
                $log->id,
                CarbonFootprintLog::class,
                "CO₂ saved: {$calc['saved']} kg on a {$data['vehicle_type']} trip"
            );
        }

        return redirect()->route('carbon.index')
                         ->with('success', "Trip logged! You saved {$calc['saved']} kg of CO₂.");
    }

    /**
     * Called internally after a ride is completed (from RideController).
     */
    public static function logRideCompletion(
        int    $userId,
        int    $rideId,
        string $vehicleType,
        float  $distanceKm,
        int    $passengers,
        bool   $isShared = true
    ): void {
        $calc = CarbonFootprintLog::calculate($vehicleType, $distanceKm, $passengers);

        $log = CarbonFootprintLog::create([
            'user_id'        => $userId,
            'ride_id'        => $rideId,
            'vehicle_type'   => $vehicleType,
            'distance_km'    => $distanceKm,
            'passengers'     => $passengers,
            'co2_saved_kg'   => $calc['saved'],
            'co2_emitted_kg' => $calc['emitted'],
            'is_shared_ride' => $isShared,
        ]);

        User::find($userId)?->increment('co2_saved_total', $calc['saved']);
    }
}
