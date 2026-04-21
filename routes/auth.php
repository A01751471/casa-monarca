<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\MigranteAuthController;
use App\Http\Controllers\Auth\MigranteRegistrationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Selección de tipo de acceso (login) y tipo de registro
    Route::get('tipo-acceso', fn() => view('auth.tipo-acceso'))
        ->name('tipo-acceso');

    Route::get('tipo-registro', fn() => view('auth.tipo-registro'))
        ->name('tipo-registro');

    // Registro de colaboradores / agentes externos
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Página de espera tras registrarse (status = pendiente)
    Route::get('espera-aprobacion', fn() => view('auth.espera-aprobacion'))
        ->name('auth.espera-aprobacion');
});

// Acceso por llave privada para migrantes
Route::get('acceso', [MigranteAuthController::class, 'showLogin'])->name('migrante.login');
Route::post('acceso', [MigranteAuthController::class, 'login'])->name('migrante.login.post')
    ->middleware('throttle:5,1');
Route::post('acceso/salir', [MigranteAuthController::class, 'logout'])->name('migrante.logout');

// Registro de migrantes: accesible para visitantes y para staff autenticado
Route::get('registro/migrante', [MigranteRegistrationController::class, 'create'])
    ->name('register.migrante');

Route::post('registro/migrante', [MigranteRegistrationController::class, 'store'])
    ->name('register.migrante.store');

Route::get('registro/migrante/exitoso', [MigranteRegistrationController::class, 'exitoso'])
    ->name('migrante.registro.exitoso');

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
