<?php

namespace App\Http\Controllers\Admin;

use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Display Reports & Analytics
     */
    public function reports()
    {
        // 1. User Performance - Top performing users
        $userPerformance = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(quiz_attempts.id) as total_attempts'),
                DB::raw('ROUND(AVG(CASE WHEN quiz_attempts.status = "completed" THEN quiz_attempts.score ELSE NULL END), 2) as avg_score'),
                DB::raw('MAX(CASE WHEN quiz_attempts.status = "completed" THEN quiz_attempts.score ELSE 0 END) as best_score')
            )
            ->leftJoin('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->leftJoin('roles', function ($join) {
                $join->on('users.id', '=', DB::raw('(SELECT model_id FROM model_has_roles WHERE model_type = "App\\\\Models\\\\User" AND role_id = roles.id LIMIT 1)'));
            })
            ->where(function ($query) {
                // Only get users who are NOT admins
                $query->whereNotIn('users.id', DB::table('model_has_roles')
                    ->where('model_type', 'App\\Models\\User')
                    ->whereIn('role_id', DB::table('roles')->where('name', 'admin')->pluck('id'))
                    ->pluck('model_id'));
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('avg_score')
            ->take(10)
            ->get();

        // 2. Most Attempted Quizzes
        $mostAttemptedQuizzes = DB::table('quizzes')
            ->select(
                'quizzes.id',
                'quizzes.title',
                'categories.name as category',
                DB::raw('COUNT(quiz_attempts.id) as total_attempts'),
                DB::raw('ROUND(AVG(quiz_attempts.score), 2) as avg_score'),
                DB::raw('COUNT(CASE WHEN quiz_attempts.status = "completed" THEN 1 END) as completed_attempts')
            )
            ->leftJoin('categories', 'quizzes.category_id', '=', 'categories.id')
            ->leftJoin('quiz_attempts', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
            ->groupBy('quizzes.id', 'quizzes.title', 'categories.name')
            ->orderByDesc('total_attempts')
            ->take(10)
            ->get();

        // 3. Average Scores Distribution
        $avgScoresDistribution = DB::table('quiz_attempts')
            ->select(
                DB::raw('ROUND(AVG(score), 2) as avg_score'),
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('DATE(completed_at) as date')
            )
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->orderBy(DB::raw('DATE(completed_at)'), 'asc')
            ->take(30)
            ->get();

        // 4. Drop-off Analysis (in-progress attempts)
        $dropoffAnalysis = DB::table('quiz_attempts')
            ->select(
                'quizzes.title as quiz_name',
                DB::raw('COUNT(CASE WHEN quiz_attempts.status = "in_progress" THEN 1 END) as in_progress_count'),
                DB::raw('COUNT(CASE WHEN quiz_attempts.status = "completed" THEN 1 END) as completed_count'),
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('ROUND((COUNT(CASE WHEN quiz_attempts.status = "completed" THEN 1 END) / COUNT(*)) * 100, 2) as completion_rate')
            )
            ->leftJoin('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->groupBy('quizzes.id', 'quizzes.title')
            ->orderByDesc('total_attempts')
            ->take(10)
            ->get();

        // 5. Summary Statistics
        $totalUsers = User::count();
        $totalQuizzes = Quiz::count();
        $totalAttempts = QuizAttempt::count();
        $avgScoreOverall = DB::table('quiz_attempts')
            ->where('status', 'completed')
            ->avg('score');

        return view('admin.reports', [
            'userPerformance' => $userPerformance,
            'mostAttemptedQuizzes' => $mostAttemptedQuizzes,
            'avgScoresDistribution' => $avgScoresDistribution,
            'dropoffAnalysis' => $dropoffAnalysis,
            'totalUsers' => $totalUsers,
            'totalQuizzes' => $totalQuizzes,
            'totalAttempts' => $totalAttempts,
            'avgScoreOverall' => round($avgScoreOverall, 2),
        ]);
    }
}
