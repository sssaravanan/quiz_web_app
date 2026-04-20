<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Fetch users with avg_score and max_score from completed attempts
        $users = User::select(
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'users.updated_at',
                DB::raw('COALESCE(AVG(CASE WHEN quiz_attempts.status = "completed" THEN quiz_attempts.score ELSE NULL END), 0) as avg_score'),
                DB::raw('COALESCE(MAX(CASE WHEN quiz_attempts.status = "completed" THEN quiz_attempts.score ELSE NULL END), 0) as max_score')
            )
            ->leftJoin('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at')
            ->get();
        
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Load user with completed attempts
        $user->load(['quizAttempts' => function ($query) {
            $query->where('status', 'completed')
                ->with(['quiz', 'answers.question.options', 'answers.selectedOption']);
        }]);

        // Calculate stats
        $attempts = $user->quizAttempts;
        $avgScore = $attempts->count() > 0 ? $attempts->avg('score') : 0;
        $maxScore = $attempts->count() > 0 ? $attempts->max('score') : 0;

        return view('admin.users.show', compact('user', 'attempts', 'avgScore', 'maxScore'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Delete user's quiz attempts and answers (cascade)
        QuizAttempt::where('user_id', $user->id)->delete();
        
        // Delete the user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    public function attempts(User $user)
    {
        // Fetch all completed attempts for the user
        $user->load(['quizAttempts' => function ($query) {
            $query->where('status', 'completed')
                ->with('quiz')
                ->orderBy('completed_at', 'desc');
        }]);

        return view('admin.users.attempts', ['user' => $user, 'attempts' => $user->quizAttempts]);
    }

    public function attemptReview(QuizAttempt $attempt)
    {
        // Load attempt with all necessary relations
        $attempt->load([
            'user',
            'quiz.questions',
            'quiz.questions.options',
            'answers.question.options',
            'answers.selectedOption'
        ]);

        // Calculate stats
        $correctCount = $attempt->answers->where('is_correct', true)->count();
        $totalQuestions = $attempt->answers->count();

        return view('admin.users.attempt-review', compact('attempt', 'correctCount', 'totalQuestions'));
    }
}
