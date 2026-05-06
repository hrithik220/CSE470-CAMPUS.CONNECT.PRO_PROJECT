<?php

namespace App\Http\Controllers;

use App\Models\DoubtQuestion;
use App\Models\DoubtAnswer;
use Illuminate\Http\Request;

class DoubtForumController extends Controller
{
    public function index()
    {
        $questions = DoubtQuestion::with(['user', 'answers.user'])
            ->latest()
            ->get();

        return view('doubt-forum.index', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:50',
            'question' => 'required|string',
        ]);

        DoubtQuestion::create([
            'user_id' => auth()->id(),
            'course_code' => $request->course_code,
            'question' => $request->question,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        return redirect('/doubt-forum')->with('success', 'Question posted successfully.');
    }

    public function storeAnswer(Request $request, $questionId)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        DoubtAnswer::create([
            'doubt_question_id' => $questionId,
            'user_id' => auth()->id(),
            'answer' => $request->answer,
        ]);

        return redirect('/doubt-forum')->with('success', 'Answer posted successfully.');
    }

    public function upvote($id)
    {
        $answer = DoubtAnswer::findOrFail($id);
        $answer->increment('votes');

        return redirect('/doubt-forum');
    }

    public function downvote($id)
    {
        $answer = DoubtAnswer::findOrFail($id);
        $answer->decrement('votes');

        return redirect('/doubt-forum');
    }
}