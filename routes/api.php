<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LevelController;
use App\Http\Controllers\Api\V1\StageController;
use App\Http\Controllers\Api\V1\MaterialController;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\DrawingController;
use App\Http\Controllers\Api\V1\ProgressController;
use App\Http\Controllers\Api\V1\LeaderboardController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\AdminDashboardController;
use App\Http\Controllers\Api\V1\TranslationController;
use App\Http\Controllers\Api\V1\FileUploadController;
use App\Http\Controllers\Api\V1\BadgeController;
use App\Http\Controllers\Api\V1\HealthController;

// API Version 1
Route::prefix('v1')->group(function () {

    // Public routes - Authentication (with rate limiting)
    Route::post('/auth/register', [AuthController::class, 'register'])
        ->middleware('throttle:register');
    Route::post('/auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');

    // Protected routes - Require authentication
    Route::middleware('auth:sanctum')->group(function () {

        // Auth endpoints
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Levels - All authenticated users
        Route::get('/levels', [LevelController::class, 'index']);
        Route::get('/levels/{id}', [LevelController::class, 'show']);

        // Stages - All authenticated users
        Route::get('/stages', [StageController::class, 'index']);
        Route::get('/stages/{id}', [StageController::class, 'show']);

        // Materials - All authenticated users
        Route::get('/stages/{stageId}/materials', [MaterialController::class, 'index']);

        // Drawing Challenges - All authenticated users
        Route::get('/stages/{stageId}/drawing', [DrawingController::class, 'show']);

        // Quizzes - All authenticated users
        Route::get('/stages/{stageId}/quiz', [QuizController::class, 'show']);

        // User Profile - Self (no ID needed, uses token)
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'update']);
        Route::post('/profile/avatar', [UserController::class, 'uploadAvatar']);

        // User Progress - Self (no ID needed, uses token)
        Route::get('/progress', [ProgressController::class, 'myProgress']);
        Route::get('/progress/levels', [ProgressController::class, 'levelsProgress']);
        Route::get('/progress/levels/{levelId}', [ProgressController::class, 'levelProgress']);

        // User Badges - Self (no ID needed, uses token)
        Route::get('/my-badges', [BadgeController::class, 'myBadges']);

        // Leaderboard - All authenticated users
        Route::get('/leaderboard/weekly', [LeaderboardController::class, 'weekly']);
        Route::get('/leaderboard/all-time', [LeaderboardController::class, 'allTime']);

        // Badges - All authenticated users (list all available badges)
        Route::get('/badges', [BadgeController::class, 'index']);

        // Translation Tool - All authenticated users
        Route::post('/translate/latin-to-javanese', [TranslationController::class, 'latinToJavanese']);
        Route::post('/translate/javanese-to-latin', [TranslationController::class, 'javaneseToLatin']);

        // Pemain only routes
        Route::middleware('check.role:pemain')->group(function () {
            // Drawing submission
            Route::post('/stages/{stageId}/submit-drawing', [DrawingController::class, 'submit'])
                ->middleware('throttle:drawing-submission');

            // Quiz submission
            Route::post('/quizzes/{quizId}/submit', [QuizController::class, 'submit']);
        });

        // Admin only routes
        Route::middleware('check.role:admin')->prefix('admin')->group(function () {

            // Dashboard & User Management
            Route::get('/dashboard', [AdminDashboardController::class, 'dashboard']);
            Route::get('/users', [AdminDashboardController::class, 'users']);
            Route::get('/users/{id}', [UserController::class, 'show']);
            Route::get('/users/{userId}/progress', [ProgressController::class, 'show']);
            Route::get('/users/{userId}/badges', [BadgeController::class, 'userBadges']);

            // File Upload
            Route::post('/upload/image', [FileUploadController::class, 'uploadImage']);
            Route::delete('/upload/image', [FileUploadController::class, 'deleteImage']);

            // Levels management
            Route::post('/levels', [LevelController::class, 'store']);
            Route::put('/levels/{id}', [LevelController::class, 'update']);
            Route::delete('/levels/{id}', [LevelController::class, 'destroy']);

            // Stages management
            Route::post('/stages', [StageController::class, 'store']);
            Route::put('/stages/{id}', [StageController::class, 'update']);
            Route::delete('/stages/{id}', [StageController::class, 'destroy']);

            // Materials management
            Route::post('/materials', [MaterialController::class, 'store']);
            Route::put('/materials/{id}', [MaterialController::class, 'update']);
            Route::delete('/materials/{id}', [MaterialController::class, 'destroy']);

            // Drawing Challenges management
            Route::post('/drawing-challenges', [DrawingController::class, 'store']);

            // Quizzes management
            Route::post('/quizzes', [QuizController::class, 'store']);
            Route::put('/quizzes/{id}', [QuizController::class, 'update']);
            Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
        });
    });

    // Health check - Public
    Route::get('/health', [HealthController::class, 'check']);
});
