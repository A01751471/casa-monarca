<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'checkstatus']) // <--- El nuevo nombre sin el :1
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mantenemos áreas si las usas, pero BORRAMOS la de admin/users
    Route::get('/admin/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/admin/areas', [AreaController::class, 'store'])->name('areas.store');

    // NUEVA RUTA DE APROBACIÓN (Sencilla y directa)
    Route::post('/usuarios/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
});
Route::view('/espera-aprobacion', 'auth.espera-aprobacion')->name('espera.aprobacion');

require __DIR__.'/auth.php';
