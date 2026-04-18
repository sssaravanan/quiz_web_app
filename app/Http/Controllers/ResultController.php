<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ResultController extends Controller
{
    public function show(QuizAttempt $attempt)
    {
        // Verify user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load('quiz.category');

        return Inertia::render('Result', [
            'attempt' => $attempt,
        ]);
    }

    public function review(QuizAttempt $attempt)
    {
        // Verify user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load(['answers.selectedOption', 'quiz']);
        
        $questions = $attempt->quiz->questions()
            ->with(['options', 'attachment'])
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'explanation' => $q->explanation,
                'options' => $q->options,
                'attachment' => $q->attachment,
            ]);

        return Inertia::render('Review', [
            'attempt' => $attempt,
            'questions' => $questions,
        ]);
    }
}
