<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AttemptsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // Redirect authenticated users to their dashboard
    if (Auth::check()) {
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Admin Routes - Only accessible to users with admin role
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('quizzes', AdminQuizController::class);
        Route::resource('questions', AdminQuestionController::class);
        
        // User routes - specific routes before resource to avoid conflicts
        Route::get('users/attempt/{attempt}', [AdminUserController::class, 'attemptReview'])->name('users.attempt.review');
        Route::resource('users', AdminUserController::class);
        Route::get('users/{user}/attempts', [AdminUserController::class, 'attempts'])->name('users.attempts');
        
        Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    });

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Attempts
    Route::get('/attempts', [AttemptsController::class, 'index'])->name('attempts.index');
    
    // Quizzes
    Route::get('/quizzes', [QuizController::class, 'list'])->name('quizzes.list');
    Route::get('/quiz/{quiz}/start', [QuizController::class, 'quizStart'])->name('quiz.start');
    Route::get('/attempt/{quizAttempt}', [QuizController::class, 'attemptStart'])->name('attempt.show');
    
    // Results & Review
    Route::get('/result/{attempt}', [ResultController::class, 'show'])->name('result.show');
    Route::get('/review/{attempt}', [ResultController::class, 'review'])->name('attempt.review');
    
    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
