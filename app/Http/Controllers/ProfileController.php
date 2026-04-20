<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile view.
     */
    public function show(): Response
    {
        $user = Auth::user();

        $stats = DB::table('quiz_attempts')
            ->select(
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_quizzes'),
                DB::raw('COUNT(CASE WHEN status = "in_progress" THEN 1 END) as in_progress'),
                DB::raw('ROUND(AVG(CASE WHEN status = "completed" THEN score ELSE NULL END), 2) as average_score'),
                DB::raw('MAX(CASE WHEN status = "completed" THEN score ELSE 0 END) as best_score')
            )
            ->where('user_id', $user->id)
            ->first();

        // Get additional stats
        $answerStats = DB::table('attempt_answers')
            ->join('options', 'attempt_answers.selected_option_id', '=', 'options.id')
            ->join('quiz_attempts', 'attempt_answers.attempt_id', '=', 'quiz_attempts.id')
            ->where('quiz_attempts.user_id', $user->id)
            ->select(
                DB::raw('COUNT(CASE WHEN options.is_correct = 1 THEN 1 END) as total_correct'),
                DB::raw('COUNT(CASE WHEN options.is_correct = 0 THEN 1 END) as total_wrong')
            )
            ->first();

        $stats->total_correct = $answerStats->total_correct ?? 0;
        $stats->total_wrong = $answerStats->total_wrong ?? 0;

        return Inertia::render('Profile', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('EditProfile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information and password.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Update name
        $user->name = $request->name;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return Redirect::route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
