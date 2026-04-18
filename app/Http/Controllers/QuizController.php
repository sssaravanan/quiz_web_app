<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function list()
    {
        $quizzes = Quiz::where('is_published', true)
            ->with('category')
            ->withCount('questions')
            ->latest()
            ->get();

        $categories = Category::all();

        return Inertia::render('QuizList', [
            'quizzes' => $quizzes,
            'categories' => $categories,
        ]);
    }

    public function start(Quiz $quiz)
    {
        $user = Auth::user();

        // Check if user has an in-progress attempt
        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->first();

        // If no in-progress attempt, create a new one
        if (!$attempt) {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'total_questions' => $quiz->questions()->count(),
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Load all questions with options and attachments
        $questions = $quiz->questions()
            ->with(['options', 'attachment'])
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'explanation' => $q->explanation,
                'options' => $q->options,
                'attachment' => $q->attachment,
            ]);

        $attempt->load('quiz', 'answers.selectedOption');

        return Inertia::render('QuizAttempt', [
            'attempt' => $attempt,
            'questions' => $questions,
        ]);
    }
}
