<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use Illuminate\Http\Request;

class TutorProfileController extends Controller
{
    public function index()
    {
        $tutors = TutorProfile::with('user')->latest()->get();
        return view('tutors.index', compact('tutors'));
    }

    public function create()
    {
        return view('tutors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subjects' => 'required|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'availability' => 'nullable|string|max:255',
            'meeting_location' => 'nullable|string|max:255',
        ]);

        TutorProfile::create([
            'user_id' => auth()->id(),
            'subjects' => $request->subjects,
            'hourly_rate' => $request->has('is_free') ? null : $request->hourly_rate,
            'is_free' => $request->has('is_free'),
            'bio' => $request->bio,
            'availability' => $request->availability,
            'meeting_location' => $request->meeting_location,
        ]);

        return redirect()->route('tutors.index')->with('success', 'Tutor profile created successfully.');
    }
}
