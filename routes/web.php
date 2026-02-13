<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardWebController;
use App\Http\Controllers\Admin\LevelWebController;
use App\Http\Controllers\Admin\StageWebController;
use App\Http\Controllers\Admin\MaterialWebController;
use App\Http\Controllers\Admin\QuizWebController;
use App\Http\Controllers\Admin\EvaluationWebController;
use App\Http\Controllers\Admin\UserWebController;
use App\Http\Controllers\Admin\BadgeWebController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Routes (Protected)
Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');

    // Resources
    Route::resource('levels', LevelWebController::class);
    Route::resource('stages', StageWebController::class);
    Route::resource('materials', MaterialWebController::class);
    Route::resource('quizzes', QuizWebController::class);
    Route::resource('evaluations', EvaluationWebController::class);
    Route::resource('users', UserWebController::class);
    Route::resource('badges', BadgeWebController::class);
});
