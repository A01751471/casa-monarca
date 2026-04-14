<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Area;
use App\Models\Certificado;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Aprueba un colaborador pendiente y genera su par de llaves RSA-2048.
     * La llave privada se muestra UNA SOLA VEZ y nunca se almacena.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update([
            'status'      => 'alta',
            'approved_by' => auth()->id(),
        ]);

        // Generar par RSA-2048
        $config = [
            'digest_alg'       => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $resource   = openssl_pkey_new($config);
        $details    = openssl_pkey_get_details($resource);
        $publicKey  = $details['key'];

        openssl_pkey_export($resource, $privateKeyPem);

        // Fingerprint = SHA-256 del PEM de la llave pública
        $fingerprint = hash('sha256', $publicKey);

        $certificado = Certificado::create([
            'user_id'     => $user->id,
            'emitido_por' => auth()->id(),
            'public_key'  => $publicKey,
            'fingerprint' => $fingerprint,
            'algoritmo'   => 'RSA-2048',
            'emitido_at'  => now(),
            'vence_at'    => now()->addYears(2),
            'status'      => 'activo',
        ]);

        ActividadLog::registrar('aprobó_usuario', $user, [
            'usuario'        => $user->name,
            'certificado_id' => $certificado->id,
            'fingerprint'    => $fingerprint,
        ]);

        // Guardar llave privada en sesión para mostrarla UNA sola vez
        session(['private_key_once' => $privateKeyPem, 'approved_user_name' => $user->name]);

        return redirect()->route('admin.aprobacion.exitosa');
    }

    public function aprobacionExitosa(): \Illuminate\View\View
    {
        // Si no hay llave en sesión, redirigir (no se puede ver dos veces)
        if (! session()->has('private_key_once')) {
            return redirect()->route('admin.users.approvals')
                ->with('status', 'La llave privada solo puede verse una vez y ya fue entregada.');
        }

        $privateKey = session()->pull('private_key_once');
        $userName   = session()->pull('approved_user_name');

        return view('admin.aprobacion-exitosa', compact('privateKey', 'userName'));
    }

    public function reject(User $user): RedirectResponse
    {
        $user->update(['status' => 'baja']);

        ActividadLog::registrar('rechazó_usuario', $user, ['usuario' => $user->name]);

        return back()->with('status', "Solicitud de {$user->name} rechazada.");
    }

    public function revoke(User $user): RedirectResponse
    {
        $user->update(['status' => 'revocacion']);

        // Revocar certificados activos
        Certificado::where('user_id', $user->id)
            ->where('status', 'activo')
            ->update(['status' => 'revocado', 'revocado_at' => now()]);

        ActividadLog::registrar('revocó_acceso', $user, ['usuario' => $user->name]);

        return back()->with('status', "Acceso revocado para {$user->name}.");
    }

    public function restore(User $user): RedirectResponse
    {
        $user->update(['status' => 'alta']);

        ActividadLog::registrar('restauró_acceso', $user, ['usuario' => $user->name]);

        return back()->with('status', "Acceso restaurado para {$user->name}.");
    }

    public function toggleRole(User $user): RedirectResponse
    {
        $nuevoRol = ($user->role_id == 3) ? 2 : 3;
        $user->update(['role_id' => $nuevoRol]);

        ActividadLog::registrar('cambió_rol', $user, [
            'usuario'   => $user->name,
            'rol_antes' => $user->getOriginal('role_id'),
            'rol_nuevo' => $nuevoRol,
        ]);

        return back()->with('status', "Rol de {$user->name} actualizado.");
    }

    public function destroy(User $user): RedirectResponse
    {
        // Revocar certificados antes de borrar
        Certificado::where('user_id', $user->id)
            ->where('status', 'activo')
            ->update(['status' => 'revocado', 'revocado_at' => now()]);

        // Registrar en log ANTES de borrar (para capturar el nombre)
        ActividadLog::registrar('borró_usuario', $user, [
            'usuario' => $user->name,
            'email'   => $user->email,
            'role_id' => $user->role_id,
            'area_id' => $user->area_id,
        ]);

        $user->delete();

        return back()->with('status', 'Usuario eliminado. Su rastro histórico se ha preservado.');
    }

    public function show(\App\Models\User $user): \Illuminate\View\View
    {
        if (auth()->user()->role_id != 1) {
            abort(403);
        }

        $usuario = $user->load(['area', 'role', 'certificados']);

        return view('admin.users.show', compact('usuario'));
    }

    public function index(): \Illuminate\View\View
    {
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado.');
        }

        $users = User::with(['area', 'role'])->orderBy('created_at', 'desc')->get();
        $areas = Area::all();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'areas', 'roles'));
    }

    public function pendingApprovals(): \Illuminate\View\View
    {
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado.');
        }

        $pendientes = User::where('status', 'pendiente')->with(['area', 'role'])->get();

        return view('admin.users.approvals', compact('pendientes'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $user->update([
            'role_id' => $request->role_id,
            'area_id' => $request->area_id,
        ]);

        ActividadLog::registrar('actualizó_usuario', $user, [
            'usuario'    => $user->name,
            'role_nuevo' => $request->role_id,
            'area_nueva' => $request->area_id,
        ]);

        return back()->with('status', "Datos de {$user->name} actualizados.");
    }
}
