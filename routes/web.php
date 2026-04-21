<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\MigranteSolicitudController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Area;

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'checkstatus'])
    ->name('dashboard');

Route::middleware(['auth', 'checkstatus'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Áreas
    Route::get('/admin/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/admin/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::get('/admin/areas/{area}', function (Area $area) {
        $user = auth()->user();
        if ($user->role_id != 1 && $user->area_id != $area->id) {
            abort(403, 'No tienes permiso para acceder a esta área.');
        }
        $area->load('users.role');
        return view('admin.areas.show', compact('area'));
    })->name('admin.areas.show');

    // Gestión de usuarios
    Route::get('/admin/usuarios',              [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/usuarios/{user}',       [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/admin/aprobaciones',          [UserController::class, 'pendingApprovals'])->name('admin.users.approvals');
    Route::patch('/admin/usuarios/{user}/update', [UserController::class, 'update'])->name('admin.users.update');

    Route::post('/usuarios/{user}/approve',    [UserController::class, 'approve'])->name('users.approve');
    Route::post('/usuarios/{user}/reject',     [UserController::class, 'reject'])->name('users.reject');
    Route::post('/usuarios/{user}/revoke',     [UserController::class, 'revoke'])->name('users.revoke');
    Route::post('/usuarios/{user}/restore',    [UserController::class, 'restore'])->name('users.restore');
    Route::post('/usuarios/{user}/toggle-role',[UserController::class, 'toggleRole'])->name('users.toggleRole');
    Route::delete('/usuarios/{user}',          [UserController::class, 'destroy'])->name('users.destroy');

    // Post-aprobación: llave privada una sola vez
    Route::get('/admin/aprobacion-exitosa',    [UserController::class, 'aprobacionExitosa'])->name('admin.aprobacion.exitosa');

    // Certificados digitales
    Route::get('/admin/certificados',                              [CertificadoController::class, 'index'])->name('admin.certificados.index');
    Route::delete('/admin/certificados/{certificado}',             [CertificadoController::class, 'destroy'])->name('admin.certificados.destroy');

    // Diagnóstico del sistema (solo admin)
    Route::get('/admin/diagnostico', [DiagnosticoController::class, 'index'])->name('admin.diagnostico');
});

// Portal de migrantes (requiere autenticación + status alta)
Route::middleware(['auth', 'checkstatus'])->prefix('mi-espacio')->name('migrante.')->group(function () {
    Route::get('/',                    [MigranteSolicitudController::class, 'dashboard'])->name('dashboard');
    Route::get('/solicitudes',         [MigranteSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/nueva',   [MigranteSolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('/solicitudes',        [MigranteSolicitudController::class, 'store'])->name('solicitudes.store');
});

require __DIR__.'/auth.php';
