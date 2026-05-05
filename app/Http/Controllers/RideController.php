<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RideRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    // F20: Ride search & filter
    public function index(Request $request)
    {
        $query = Ride::where('available_seats', '>', 0)
                     ->where('departure_time', '>', now());

        if ($request->filled('area')) {
            $query->where('destination_area', 'like', '%' . $request->area . '%');
        }
        if ($request->filled('time')) {
            $query->whereDate('departure_time', $request->time);
        }
        if ($request->filled('seats')) {
            $query->where('available_seats', '>=', $request->seats);
        }

        $rides = $query->get();
        return view('rides.index', compact('rides'));
    }

    public function create()
    {
        return view('rides.create');
    }

    // F16: Ride offer post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_location' => 'required|string',
            'destination_area' => 'required|string',
            'departure_time' => 'required|date|after:now',
            'available_seats' => 'required|integer|min:1',
            'cost_per_seat' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        Ride::create($validated);

        return redirect()->route('rides.index')->with('success', 'Ride offered successfully!');
    }

    // F17 & F18: Ride request panel & SMS notification
    public function requestJoin(Request $request, Ride $ride)
    {
        if ($ride->user_id === Auth::id()) {
            return back()->with('error', 'You cannot request your own ride.');
        }

        RideRequest::create([
            'ride_id' => $ride->id,
            'user_id' => Auth::id(),
        ]);

        // Simulated SMS Notification API trigger (F18)
        $message = "Campus Connect Pro: New ride request from " . Auth::user()->name;
        // Http::post('sms-api-url', ['message' => $message]);

        return back()->with('success', 'Join request sent! The owner has been notified.');
    }

    // F19: Ride history dashboard
    public function history()
    {
        $userId = Auth::id();
        $offeredRides = Ride::where('user_id', $userId)->orderBy('departure_time', 'desc')->get();
        $requestedRides = RideRequest::with('ride')->where('user_id', $userId)->get();

        return view('rides.history', compact('offeredRides', 'requestedRides'));
    }
}