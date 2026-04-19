<?php

namespace App\Http\Controllers\API;

use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class AttemptController extends Controller
{
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'question_id' => 'required|exists:questions,id',
            'selected_option_id' => 'nullable|exists:options,id',
            'is_flagged' => 'nullable|boolean',
        ]);

        $attempt = QuizAttempt::findOrFail($request->attempt_id);

        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $answer = AttemptAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'selected_option_id' => $request->selected_option_id,
                'is_flagged' => $request->is_flagged ?? false,
            ]
        );

        return response()->json([
            'success' => true,
            'answer' => $answer,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
        ]);

        $attempt = QuizAttempt::with(['answers.selectedOption', 'quiz.questions'])->findOrFail($request->attempt_id);

        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $correctCount = 0;
        $totalQuestions = $attempt->total_questions;

        foreach ($attempt->answers as $answer) {
            if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        $attempt->update([
            'score' => $score,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'score' => $score,
        ]);
    }

    public function startQuiz(Quiz $quiz)
    {
        $user = Auth::user();

        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'total_questions' => $quiz->questions()->count(),
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
        ]);
    }
}
