<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Area;
use App\Models\Role; // <-- Agregamos esto para poder llamar a los roles fácilmente
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function approve(User $user)
    {
        $user->update([
            'status' => 'alta',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('status', "¡Listo! Usuario {$user->name} aprobado exitosamente.");
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'baja']);
        return back()->with('status', "Solicitud de {$user->name} rechazada (Estatus: Baja).");
    }

    public function revoke(User $user)
    {
        $user->update(['status' => 'revocacion']);
        return back()->with('status', "Acceso revocado para {$user->name}.");
    }

    public function restore(User $user)
    {
        $user->update(['status' => 'alta']);
        return back()->with('status', "Acceso ratificado para {$user->name}.");
    }

    public function toggleRole(User $user)
    {
        // Si es Operativo (3), sube a Coordinador (2). Si no, baja a Operativo (3).
        $nuevoRol = ($user->role_id == 3) ? 2 : 3;
        $user->update(['role_id' => $nuevoRol]);
        
        return back()->with('status', "Rol de {$user->name} actualizado exitosamente.");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('status', 'Usuario borrado de la base de datos de forma permanente.');
    }

    public function index()
    {
        // Candado de seguridad: Si NO es Admin (1), lo bloqueamos
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado: Esta sección es exclusiva para Administradores.');
        }

        // 1. Obtenemos todos los usuarios
        $users = User::with(['area', 'role'])->orderBy('created_at', 'desc')->get();
        
        // 2. NUEVO: Obtenemos todas las áreas y roles para llenar tu tabla
        $areas = Area::all();
        $roles = Role::all();

        // 3. Enviamos todo a la vista
        return view('admin.users.index', compact('users', 'areas', 'roles'));
    }

    public function pendingApprovals()
    {
        // Candado de seguridad: Solo Admin (Rol 1)
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado.');
        }

        // Traemos solo a los usuarios pendientes
        $pendientes = User::where('status', 'pendiente')->with(['area', 'role'])->get();

        return view('admin.users.approvals', compact('pendientes'));
    }
    public function update(Request $request, User $user)
    {
        // Candado de seguridad: Solo Admin
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado.');
        }

        // 1. Validamos que la información que llega del formulario sea correcta
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'area_id' => 'nullable|exists:areas,id', // nullable porque puede ser un Migrante sin área
        ]);

        // 2. Actualizamos al usuario en la base de datos
        $user->update([
            'role_id' => $request->role_id,
            'area_id' => $request->area_id,
        ]);

        // 3. Regresamos a la vista con un mensaje de éxito
        return back()->with('status', "¡Listo! Los datos de {$user->name} se han actualizado correctamente.");
    }
}