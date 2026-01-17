<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RsvpController;
use App\Http\Controllers\Api\GuestbookController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public APIs (cho thiệp)
Route::post('/rsvp/{cardId}', [RsvpController::class, 'store']);
Route::get('/guestbook/{cardId}', [GuestbookController::class, 'index']);
Route::post('/guestbook/{cardId}', [GuestbookController::class, 'store']);

// Payment callback (secured by token)
Route::post('/payment/callback', [PaymentController::class, 'callback']);

// Authenticated APIs
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // RSVP management
    Route::get('/rsvp/{cardId}', [RsvpController::class, 'index']);
    
    // Guestbook management
    Route::delete('/guestbook/{cardId}/{messageId}', [GuestbookController::class, 'destroy']);
    
    // Payment info
    Route::get('/payment/qr-info', [PaymentController::class, 'qrInfo']);
});

