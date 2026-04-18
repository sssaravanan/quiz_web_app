<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('ROUND(AVG(CASE WHEN quiz_attempts.status = "completed" THEN quiz_attempts.score ELSE NULL END), 2) as avg_score'),
                DB::raw('COUNT(CASE WHEN quiz_attempts.status = "completed" THEN 1 END) as total_attempts'),
                DB::raw('MAX(CASE WHEN quiz_attempts.status = "completed" THEN quiz_attempts.score ELSE 0 END) as best_score')
            )
            ->leftJoin('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('avg_score')
            ->get();

        return Inertia::render('Leaderboard', [
            'leaderboard' => $leaderboard,
        ]);
    }
}
