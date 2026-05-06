<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use App\Models\TutoringSession;
use App\Models\SmsReminder;
use Illuminate\Http\Request;

class TutoringSessionController extends Controller
{
    // Show booking form
    public function create($tutorId)
    {
        $tutor = TutorProfile::with('user')->findOrFail($tutorId);
        return view('tutoring-sessions.create', compact('tutor'));
    }

    // Store session + create SMS reminders (F14)
    public function store(Request $request)
    {
        $request->validate([
            'tutor_profile_id' => 'required|exists:tutor_profiles,id',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'meeting_location' => 'required|string|max:255',
        ]);

        // Create session
        $session = TutoringSession::create([
            'student_id' => auth()->id(),
            'tutor_profile_id' => $request->tutor_profile_id,
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
            'meeting_location' => $request->meeting_location,
            'status' => 'pending',
        ]);

        // Create SMS reminder for student
        SmsReminder::create([
            'tutoring_session_id' => $session->id,
            'user_id' => auth()->id(),
            'message' => 'Reminder: Your tutoring session is on ' 
                . $session->session_date . ' at ' 
                . $session->session_time . ' in ' 
                . $session->meeting_location,
            'status' => 'scheduled',
        ]);

        // Create SMS reminder for tutor
        SmsReminder::create([
            'tutoring_session_id' => $session->id,
            'user_id' => $session->tutorProfile->user_id,
            'message' => 'Reminder: You have a tutoring session on ' 
                . $session->session_date . ' at ' 
                . $session->session_time . ' in ' 
                . $session->meeting_location,
            'status' => 'scheduled',
        ]);

        return redirect('/tutoring-sessions')
            ->with('success', 'Session booked successfully. SMS reminders scheduled.');
    }

    // Show all sessions
    public function index()
    {
        $sessions = TutoringSession::with(['student', 'tutorProfile.user'])
            ->latest()
            ->get();

        return view('tutoring-sessions.index', compact('sessions'));
    }
}