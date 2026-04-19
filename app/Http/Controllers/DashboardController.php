<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Cache key per user
        $cacheKey = "dashboard_data_user_{$userId}";

        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($userId) {

            $quizzes = Quiz::with(['category:id,name'])
                ->withCount('questions')
                ->where('is_published', true)
                ->latest()
                ->limit(6)
                ->get();

            $recentAttempts = QuizAttempt::query()
                ->select(['id', 'quiz_id', 'score', 'status', 'created_at'])
                ->where('user_id', $userId)
                ->with(['quiz:id,title,category_id', 'quiz.category:id,name'])
                ->latest()
                ->limit(5)
                ->get();

            $leaderboard = \DB::table('users')
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

            return [
                'quizzes' => $quizzes,
                'recentAttempts' => $recentAttempts,
                'leaderboard' => $leaderboard,
            ];
        });

        return Inertia::render('Dashboard', $data);
    }
}
