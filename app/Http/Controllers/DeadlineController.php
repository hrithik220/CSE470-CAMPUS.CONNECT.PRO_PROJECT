<?php

namespace App\Http\Controllers;

use App\Models\Deadline;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeadlineController extends Controller
{
    public function index()
    {
        $deadlines = Deadline::where('user_id', auth()->id())
            ->orderBy('deadline_date')
            ->orderBy('deadline_time')
            ->get();

        return view('deadlines.index', compact('deadlines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'priority' => 'required|string',
            'deadline_date' => 'required|date',
            'deadline_time' => 'required',
            'description' => 'nullable|string',
        ]);

        Deadline::create([
            'user_id' => auth()->id(),
            'course_code' => $request->course_code,
            'title' => $request->title,
            'type' => $request->type,
            'priority' => $request->priority,
            'deadline_date' => $request->deadline_date,
            'deadline_time' => $request->deadline_time,
            'description' => $request->description,
            'is_completed' => false,
        ]);

        return redirect('/deadlines')->with('success', 'Deadline added successfully.');
    }

    public function complete($id)
    {
        $deadline = Deadline::where('user_id', auth()->id())->findOrFail($id);
        $deadline->is_completed = true;
        $deadline->save();

        return redirect('/deadlines')->with('success', 'Deadline marked as completed.');
    }

    public function destroy($id)
    {
        $deadline = Deadline::where('user_id', auth()->id())->findOrFail($id);
        $deadline->delete();

        return redirect('/deadlines')->with('success', 'Deadline deleted successfully.');
    }
}