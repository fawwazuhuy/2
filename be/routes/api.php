<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MesinController;
use App\Http\Controllers\ShiftController;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;





RateLimiter::for('api', function () {
    return Limit::perMinute(180);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/protected-data', function (Request $request) {
    return response()->json([
        'message' => 'Authorized access',
        'user' => $request->user(),
    ]);
});


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/mesin', [MesinController::class, 'index']);
    // Route::post('/Mesin', [MesinController::class, 'store']);
    // Route::delete('/Mesin/{Mesin}', [MesinController::class, 'destroy']);

    Route::get('/shift', [ShiftController::class, 'index']);
    // Route::post('/Mesin', [MesinController::class, 'store']);
    // Route::delete('/Mesin/{Mesin}', [MesinController::class, 'destroy']);

    
});

// Route::Mesin('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest')
//     ->name('register');
