<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaSolicitud;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AreaMembresiaController extends Controller
{
    // ─── Vista "Mi Área" para el usuario sin área ────────────────────────────

    public function miArea(): View
    {
        $user = auth()->user();

        // Solo roles 3-4 usan esta vista; coordinadores tienen su propia vista de área
        if ($user->role_id <= 2 || $user->role_id >= 5) {
            abort(403);
        }

        $solicitudPendiente = $user->areaSolicitudPendiente;

        // Áreas disponibles (excluir TI interna)
        $areas = Area::where('id', '!=', 6)->orderBy('nombre')->get();

        return view('staff.mi-area', compact('user', 'solicitudPendiente', 'areas'));
    }

    // ─── Solicitar unirse a un área ──────────────────────────────────────────

    public function solicitarArea(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role_id <= 2 || $user->role_id >= 5) {
            abort(403);
        }

        // No puede pedir si ya tiene una solicitud pendiente
        if ($user->areaSolicitudPendiente) {
            return back()->with('error', 'Ya tienes una solicitud pendiente. Espera a que sea revisada o cancélala primero.');
        }

        // Advertir si ya pertenece a un área (es una transferencia)
        if ($user->area_id && (int) $request->area_id === $user->area_id) {
            return back()->with('error', 'Ya eres miembro de esa área.');
        }

        $request->validate([
            'area_id' => ['required', 'exists:areas,id'],
            'nota'    => ['nullable', 'string', 'max:500'],
        ]);

        AreaSolicitud::create([
            'user_id' => $user->id,
            'area_id' => $request->area_id,
            'nota'    => $request->nota,
            'status'  => 'pendiente',
        ]);

        return back()->with('status', 'Solicitud enviada. El coordinador del área revisará tu petición.');
    }

    // ─── Cancelar solicitud propia ───────────────────────────────────────────

    public function cancelarSolicitud(): RedirectResponse
    {
        $solicitud = auth()->user()->areaSolicitudPendiente;

        if (!$solicitud) {
            return back()->with('error', 'No tienes una solicitud pendiente que cancelar.');
        }

        $solicitud->delete();

        return back()->with('status', 'Solicitud cancelada.');
    }

    // ─── Vista: usuarios sin área (admin y coordinadores) ────────────────────

    public function sinArea(): View
    {
        $user = auth()->user();

        if ($user->role_id > 2) {
            abort(403);
        }

        $esAdmin = $user->role_id === 1;

        // Usuarios activos (role 3-4) sin área asignada
        $sinArea = User::whereIn('role_id', [3, 4])
            ->where('status', 'alta')
            ->whereNull('area_id')
            ->with('role')
            ->orderBy('name')
            ->get();

        // Solicitudes pendientes: admin ve todas, coordinador solo las de su área
        $solicitudesQuery = AreaSolicitud::where('status', 'pendiente')
            ->with(['user.role', 'area']);

        if (!$esAdmin) {
            $solicitudesQuery->where('area_id', $user->area_id);
        }

        $solicitudes = $solicitudesQuery->oldest()->get();

        // Áreas para el dropdown de asignación directa
        $areas = $esAdmin
            ? Area::where('id', '!=', 6)->orderBy('nombre')->get()
            : Area::where('id', $user->area_id)->get();

        return view('admin.sin-area', compact('sinArea', 'solicitudes', 'areas', 'esAdmin'));
    }

    // ─── Aprobar solicitud de membresía ──────────────────────────────────────

    public function aprobar(AreaSolicitud $solicitud): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role_id > 2) {
            abort(403);
        }

        // Coordinador solo puede aprobar solicitudes de su área
        if ($user->role_id === 2 && $solicitud->area_id !== $user->area_id) {
            abort(403, 'Esta solicitud no pertenece a tu área.');
        }

        if ($solicitud->status !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $miembro      = $solicitud->user;
        $areaAnterior = $miembro->area?->nombre;

        $miembro->update(['area_id' => $solicitud->area_id]);

        $solicitud->update([
            'status'       => 'aprobada',
            'revisado_por' => $user->id,
            'revisado_at'  => now(),
        ]);

        // Cancelar otras solicitudes pendientes del mismo usuario (no debería haber, pero por seguridad)
        AreaSolicitud::where('user_id', $miembro->id)
            ->where('status', 'pendiente')
            ->where('id', '!=', $solicitud->id)
            ->update(['status' => 'rechazada', 'revisado_por' => $user->id, 'revisado_at' => now()]);

        $msg = $areaAnterior
            ? "{$miembro->name} transferido de {$areaAnterior} a {$solicitud->area->nombre}."
            : "{$miembro->name} ahora es miembro de {$solicitud->area->nombre}.";

        return back()->with('status', $msg);
    }

    // ─── Rechazar solicitud de membresía ─────────────────────────────────────

    public function rechazar(AreaSolicitud $solicitud): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role_id > 2) {
            abort(403);
        }

        if ($user->role_id === 2 && $solicitud->area_id !== $user->area_id) {
            abort(403, 'Esta solicitud no pertenece a tu área.');
        }

        if ($solicitud->status !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $solicitud->update([
            'status'      => 'rechazada',
            'revisado_por' => $user->id,
            'revisado_at' => now(),
        ]);

        return back()->with('status', "Solicitud de {$solicitud->user->name} rechazada.");
    }

    // ─── Asignar usuario directamente a un área ──────────────────────────────

    public function asignarDirecto(Request $request): RedirectResponse
    {
        $actor = auth()->user();

        if ($actor->role_id > 2) {
            abort(403);
        }

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'area_id' => ['required', 'exists:areas,id'],
        ]);

        $objetivo = User::findOrFail($request->user_id);

        // Solo se pueden asignar usuarios de rol 3 o 4 a áreas
        if (!in_array($objetivo->role_id, [3, 4])) {
            return back()->with('error', 'Solo se pueden asignar colaboradores y voluntarios a áreas.');
        }

        // Coordinador solo puede asignar a su propia área
        if ($actor->role_id === 2 && (int) $request->area_id !== $actor->area_id) {
            return back()->with('error', 'Solo puedes asignar usuarios a tu propia área.');
        }

        // Cancelar cualquier solicitud pendiente del usuario
        AreaSolicitud::where('user_id', $objetivo->id)
            ->where('status', 'pendiente')
            ->update([
                'status'      => 'aprobada',
                'revisado_por' => $actor->id,
                'revisado_at' => now(),
            ]);

        $areaAnterior = $objetivo->area?->nombre;
        $objetivo->update(['area_id' => $request->area_id]);
        $areaNueva = Area::find($request->area_id)?->nombre;

        $msg = $areaAnterior
            ? "{$objetivo->name} movido de {$areaAnterior} a {$areaNueva}."
            : "{$objetivo->name} asignado a {$areaNueva}.";

        return back()->with('status', $msg);
    }

    // ─── Remover usuario de su área ──────────────────────────────────────────

    public function removerDeArea(User $usuario): RedirectResponse
    {
        $actor = auth()->user();

        if ($actor->role_id > 2) {
            abort(403);
        }

        // Coordinador solo puede remover de su área
        if ($actor->role_id === 2 && $usuario->area_id !== $actor->area_id) {
            abort(403, 'Este usuario no pertenece a tu área.');
        }

        $area = $usuario->area?->nombre;
        $usuario->update(['area_id' => null]);

        return back()->with('status', "{$usuario->name} removido de {$area}.");
    }
}
