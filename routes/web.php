<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardController;
use App\Livewire\Editor\EditorPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - MoiBan.vn
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/templates', function () {
    $templates = \App\Models\Template::active()->get();
    return view('templates.index', compact('templates'));
})->name('templates.index');

Route::get('/pricing', function () {
    $subscriptions = \App\Models\Subscription::active()->get();
    return view('pricing', compact('subscriptions'));
})->name('pricing');

// Public Card View (thiệp công khai)
Route::get('/c/{slug}', [CardController::class, 'show'])->name('cards.public');

// ==================== AUTH ROUTES (Breeze) ====================

Route::get('/dashboard', function () {
    return redirect()->route('cards.my-cards');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // My Cards
    Route::get('/my-cards', [CardController::class, 'myCards'])->name('cards.my-cards');
    
    // Card CRUD
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    
    // Livewire Editor
    Route::get('/cards/{card}/edit', EditorPage::class)->name('cards.edit');
    
    // Wallet
    Route::get('/wallet', function () {
        return view('wallet.index');
    })->name('wallet.index');
});

// ==================== ADMIN ROUTES ====================

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Middleware check admin
    Route::middleware(function ($request, $next) {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return $next($request);
    })->group(function () {
        // Dashboard
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        // Users Management
        Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin.users');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('admin.users.show');
        Route::patch('/users/{user}/toggle-active', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleActive'])->name('admin.users.toggle-active');
        Route::patch('/users/{user}/set-role', [\App\Http\Controllers\Admin\AdminUserController::class, 'setRole'])->name('admin.users.set-role');
        Route::post('/users/{user}/add-balance', [\App\Http\Controllers\Admin\AdminUserController::class, 'addBalance'])->name('admin.users.add-balance');
        
        // Templates Management
        Route::get('/templates', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'index'])->name('admin.templates.index');
        Route::get('/templates/create', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'create'])->name('admin.templates.create');
        Route::post('/templates', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'store'])->name('admin.templates.store');
        Route::get('/templates/{template}/edit', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'edit'])->name('admin.templates.edit');
        Route::put('/templates/{template}', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'update'])->name('admin.templates.update');
        Route::patch('/templates/{template}/toggle-active', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'toggleActive'])->name('admin.templates.toggle-active');
        Route::delete('/templates/{template}', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'destroy'])->name('admin.templates.destroy');
        Route::post('/templates/sync', [\App\Http\Controllers\Admin\AdminTemplateController::class, 'sync'])->name('admin.templates.sync');
        
        // Settings Management
        Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('admin.settings');
        Route::put('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('admin.settings.update');
        Route::post('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'store'])->name('admin.settings.store');
        Route::delete('/settings/{key}', [\App\Http\Controllers\Admin\AdminSettingController::class, 'destroy'])->name('admin.settings.destroy');
    });
});

require __DIR__.'/auth.php';

