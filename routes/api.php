<?php

use App\Http\Controllers\API\AttemptController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Attempt endpoints
    Route::post('/attempt/save-answer', [AttemptController::class, 'saveAnswer']);
    Route::post('/attempt/submit', [AttemptController::class, 'submit']);
    Route::post('/quiz/{quiz}/start', [AttemptController::class, 'startQuiz']);
});