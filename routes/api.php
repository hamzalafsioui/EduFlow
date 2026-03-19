<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', function ($token) {
        return response()->json(['token' => $token, 'email' => request('email')]);
    })->name('password.reset');
    Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'userProfile']);
});

Route::middleware(['api', 'auth:api'])->group(function () {
    // Wishlist Management
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/courses/{course}', [WishlistController::class, 'toggle']);

    // Course Management
    Route::get('courses/recommended', [CourseController::class, 'recommended']);
    Route::apiResource('courses', CourseController::class);
});
