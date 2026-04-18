<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $quizzes = Quiz::with('category')
            ->where('is_published', true)
            ->withCount('questions')
            ->latest()
            ->get();

        $recentAttempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz')
            ->latest()
            ->take(10)
            ->get();

        $leaderboardTop = \DB::table('users')
            ->select(
                'users.id',
                'users.name',
                \DB::raw('ROUND(AVG(quiz_attempts.score), 2) as avg_score')
            )
            ->leftJoin('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->where('quiz_attempts.status', 'completed')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'quizzes' => $quizzes,
            'recentAttempts' => $recentAttempts,
            'leaderboardTop' => $leaderboardTop,
        ]);
    }
}
