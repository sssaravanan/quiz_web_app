<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttemptsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz.category:id,name')
            ->latest()
            ->paginate(10);

        return Inertia::render('Attempts', [
            'attempts' => $attempts,
        ]);
    }
}
