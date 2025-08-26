<?php

namespace App\Http\Controllers;

use App\Models\QuizSet;
use Illuminate\Http\Request;

class QuizSetController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $quizSet = QuizSet::create($validated);
        return response()->json($quizSet);
    }

    public function update(Request $request, QuizSet $quizSet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $quizSet->update($validated);
        return response()->json($quizSet);
    }
}
