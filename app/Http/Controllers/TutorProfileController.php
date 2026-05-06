<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use Illuminate\Http\Request;

class TutorProfileController extends Controller
{
    public function index()
    {
        $tutors = TutorProfile::with('user')->get();
        return view('tutors.index', compact('tutors'));
    }

    public function create()
    {
        return view('tutors.create');
    }

    public function store(Request $request)
    {
        TutorProfile::create([
            'user_id' => auth()->id(),
            'subjects' => $request->subjects,
            'hourly_rate' => $request->hourly_rate,
            'bio' => $request->bio,
            'availability' => $request->availability,
            'meeting_location' => $request->meeting_location,
            'is_free' => $request->has('is_free'),
        ]);

        return redirect('/tutors');
    }
}