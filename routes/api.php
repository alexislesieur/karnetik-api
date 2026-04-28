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
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\FuelLogController;
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

// ── Véhicules ─────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
    Route::put('/vehicles/{id}', [VehicleController::class, 'update']);
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy']);

    // ── Interventions (par véhicule) ──────────────
    Route::get('/vehicles/{vehicleId}/interventions', [InterventionController::class, 'index']);
    Route::post('/vehicles/{vehicleId}/interventions', [InterventionController::class, 'store']);
    Route::get('/vehicles/{vehicleId}/interventions/{id}', [InterventionController::class, 'show']);
    Route::put('/vehicles/{vehicleId}/interventions/{id}', [InterventionController::class, 'update']);
    Route::delete('/vehicles/{vehicleId}/interventions/{id}', [InterventionController::class, 'destroy']);

    // ── Carburant (par véhicule) ──────────────────
    Route::get('/vehicles/{vehicleId}/fuel-logs', [FuelLogController::class, 'index']);
    Route::post('/vehicles/{vehicleId}/fuel-logs', [FuelLogController::class, 'store']);
    Route::get('/vehicles/{vehicleId}/fuel-logs/{id}', [FuelLogController::class, 'show']);
    Route::put('/vehicles/{vehicleId}/fuel-logs/{id}', [FuelLogController::class, 'update']);
    Route::delete('/vehicles/{vehicleId}/fuel-logs/{id}', [FuelLogController::class, 'destroy']);
});

// ── Waitlist ──────────────────────────────────────
Route::post('/waitlist', [WaitlistController::class, 'store']);

// ── Admin ─────────────────────────────────────────
Route::prefix('admin')->middleware(['auth:sanctum', \App\Http\Middleware\EnsureIsAdmin::class])->group(function () {
    Route::get('/waitlist', [AdminWaitlistController::class, 'index']);
    Route::delete('/waitlist/{id}', [AdminWaitlistController::class, 'destroy']);
    Route::get('/waitlist/export', [AdminWaitlistController::class, 'export']);
});