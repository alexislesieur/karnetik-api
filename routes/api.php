<?php

use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\Admin\WaitlistController as AdminWaitlistController;
use Illuminate\Support\Facades\Route;

// ── Auth publiques ────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);
});

// ── Auth protégées ────────────────────────────────
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/me', MeController::class);
    Route::post('/email/verify/send', [EmailVerificationController::class, 'send']);
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::delete('/account', [AccountController::class, 'destroy']);
});

// ── Profil utilisateur ────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user', [UserController::class, 'update']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
});

// ── Waitlist ──────────────────────────────────────
Route::post('/waitlist', [WaitlistController::class, 'store']);

// ── Admin ─────────────────────────────────────────
Route::prefix('admin')->middleware(['auth:sanctum', \App\Http\Middleware\EnsureIsAdmin::class])->group(function () {
    Route::get('/waitlist', [AdminWaitlistController::class, 'index']);
});