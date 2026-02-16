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

// Temporary route to seed materials (REMOVE IN PRODUCTION)
Route::get('/seed-materials', function () {
    $aksaraJawa = [
        'ꦲ' => ['name' => 'Ha', 'description' => 'Aksara dasar pertama'],
        'ꦤ' => ['name' => 'Na', 'description' => 'Aksara kedua'],
        'ꦕ' => ['name' => 'Ca', 'description' => 'Aksara ketiga'],
        'ꦫ' => ['name' => 'Ra', 'description' => 'Aksara keempat'],
        'ꦏ' => ['name' => 'Ka', 'description' => 'Aksara kelima'],
        'ꦢ' => ['name' => 'Da', 'description' => 'Aksara keenam'],
        'ꦠ' => ['name' => 'Ta', 'description' => 'Aksara ketujuh'],
        'ꦱ' => ['name' => 'Sa', 'description' => 'Aksara kedelapan'],
        'ꦮ' => ['name' => 'Wa', 'description' => 'Aksara kesembilan'],
        'ꦭ' => ['name' => 'La', 'description' => 'Aksara kesepuluh'],
        'ꦥ' => ['name' => 'Pa', 'description' => 'Aksara kesebelas'],
        'ꦝ' => ['name' => 'Dha', 'description' => 'Aksara kedua belas'],
        'ꦗ' => ['name' => 'Ja', 'description' => 'Aksara ketiga belas'],
        'ꦪ' => ['name' => 'Ya', 'description' => 'Aksara keempat belas'],
        'ꦚ' => ['name' => 'Nya', 'description' => 'Aksara kelima belas'],
        'ꦩ' => ['name' => 'Ma', 'description' => 'Aksara keenam belas'],
        'ꦒ' => ['name' => 'Ga', 'description' => 'Aksara ketujuh belas'],
        'ꦧ' => ['name' => 'Ba', 'description' => 'Aksara kedelapan belas'],
        'ꦛ' => ['name' => 'Tha', 'description' => 'Aksara kesembilan belas'],
        'ꦔ' => ['name' => 'Nga', 'description' => 'Aksara kedua puluh'],
    ];

    $aksaraKeys = array_keys($aksaraJawa);
    $aksaraArray = array_values($aksaraJawa);

    $level1Stages = \App\Models\Stage::whereHas('level', fn($q) => $q->where('level_number', 1))
        ->orderBy('stage_number')->get();

    $count = 0;
    foreach ($level1Stages as $index => $stage) {
        if ($index >= count($aksaraArray)) break;

        $aksara = $aksaraKeys[$index];
        $info = $aksaraArray[$index];

        if (\App\Models\Material::where('stage_id', $stage->id)->exists()) continue;

        \App\Models\Material::create([
            'stage_id' => $stage->id,
            'title' => "Mengenal Aksara {$info['name']}",
            'content_text' => "Pelajari aksara {$info['name']} ({$aksara})",
            'content_markdown' => "# Aksara {$info['name']}\n\n{$aksara}\n\n{$info['description']}",
            'image_url' => "/storage/aksara/{$info['name']}.png",
            'order_index' => 1,
        ]);

        $stage->update(['title' => "Aksara {$info['name']}"]);
        $count++;
    }

    return "Seeded {$count} materials. Total: " . \App\Models\Material::count();
});

// Temporary route to run migrations (REMOVE IN PRODUCTION)
Route::get('/run-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
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
