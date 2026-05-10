<?php

namespace App\Http\Controllers;

use App\Models\DoubtQuestion;
use App\Models\DoubtAnswer;
use Illuminate\Http\Request;

class DoubtForumController extends Controller
{
    public function index()
    {
        $questions = DoubtQuestion::with(['user', 'answers.user'])->latest()->get();
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

        return redirect()->route('doubt-forum.index')->with('success', 'Question posted successfully.');
    }

    public function storeAnswer(Request $request, $id)
    {
        $request->validate(['answer' => 'required|string']);

        DoubtAnswer::create([
            'doubt_question_id' => $id,
            'user_id' => auth()->id(),
            'answer' => $request->answer,
            'votes' => 0,
        ]);

        return redirect()->route('doubt-forum.index')->with('success', 'Answer posted successfully.');
    }

    public function upvote($id)
    {
        DoubtAnswer::findOrFail($id)->increment('votes');
        return redirect()->route('doubt-forum.index');
    }

    public function downvote($id)
    {
        DoubtAnswer::findOrFail($id)->decrement('votes');
        return redirect()->route('doubt-forum.index');
    }
}
