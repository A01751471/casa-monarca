<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Area;


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

    Route::get('/admin/areas/{area}', function (Area $area) {
        $user = auth()->user();
        if ($user->role_id != 1 && $user->area_id != $area->id) {
            abort(403, 'No tienes permiso para acceder a esta área.');
        }
        $area->load('users.role'); 
        return view('admin.areas.show', compact('area'));
    })->name('admin.areas.show');
});
// Rutas de gestión de usuarios
Route::post('/usuarios/{user}/approve', [UserController::class, 'approve'])->name('users.approve'); // La que ya tenías
Route::post('/usuarios/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
Route::post('/usuarios/{user}/revoke', [UserController::class, 'revoke'])->name('users.revoke');
Route::post('/usuarios/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::post('/usuarios/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggleRole');
Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');
// Ruta para ver a todos los usuarios (Solo Admin)
Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.users.index');
// Ruta para la Bandeja de Accesos Pendientes (Solo Admin)
Route::get('/admin/aprobaciones', [UserController::class, 'pendingApprovals'])->name('admin.users.approvals');
// Ruta para actualizar el rol y área de un usuario desde el Directorio Global
Route::patch('/admin/usuarios/{user}/update', [UserController::class, 'update'])->name('admin.users.update');

require __DIR__.'/auth.php';
