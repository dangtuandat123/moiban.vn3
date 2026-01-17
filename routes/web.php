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
    Route::get('/', function () {
        // Kiểm tra quyền admin
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Placeholder routes cho admin panels
    Route::get('/users', function () {
        return 'Admin Users - Coming soon';
    })->name('admin.users');
    
    Route::get('/templates', function () {
        return 'Admin Templates - Coming soon';
    })->name('admin.templates');
    
    Route::get('/settings', function () {
        return 'Admin Settings - Coming soon';
    })->name('admin.settings');
});

require __DIR__.'/auth.php';

