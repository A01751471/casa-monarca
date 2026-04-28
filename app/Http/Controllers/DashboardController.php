<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaSolicitud;
use App\Models\Certificado;
use App\Models\Expediente;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role_id === 5) {
            return redirect()->route('migrante.dashboard');
        }

        if ($user->role_id === 1) {
            return $this->dashboardAdmin($user);
        }

        if ($user->role_id === 2) {
            return $this->dashboardCoordinador($user);
        }

        if ($user->role_id === 3) {
            return $this->dashboardOperativo($user);
        }

        // Nivel 4 — Usuario (becarios, voluntarios, servicio social, recepción)
        return $this->dashboardUsuario($user);
    }

    private function dashboardAdmin($user)
    {
        $totalActivos    = User::where('status', 'alta')->whereIn('role_id', [2, 3, 4])->count();
        $totalPendientes = User::where('status', 'pendiente')->count();
        $totalCerrados   = Expediente::where('status', 'terminado')->count();
        $totalCerts      = Certificado::where('status', 'activo')->count();

        $areas = Area::withCount([
            'users as colaboradores_activos' => fn($q) => $q->where('status', 'alta'),
        ])->get();

        return view('admin.dashboard', compact(
            'totalActivos', 'totalPendientes', 'totalCerrados', 'totalCerts', 'areas'
        ));
    }

    private function dashboardCoordinador($user)
    {
        $areas               = Area::where('id', $user->area_id)->withCount('users')->get();
        $totalUsuarios       = User::where('area_id', $user->area_id)->where('status', 'alta')->count();
        $pendientes          = User::where('area_id', $user->area_id)->where('status', 'pendiente')->get();
        $solicitudesMembresia = AreaSolicitud::where('area_id', $user->area_id)->where('status', 'pendiente')->count();
        $sinArea             = null;
        $solicitudPendiente  = null;

        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes', 'solicitudesMembresia', 'sinArea', 'solicitudPendiente'));
    }

    private function dashboardOperativo($user)
    {
        $areas               = collect();
        $totalUsuarios       = 0;
        $pendientes          = collect();
        $solicitudesMembresia = 0;
        $sinArea             = null;
        $solicitudPendiente  = $user->areaSolicitudPendiente;

        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes', 'solicitudesMembresia', 'sinArea', 'solicitudPendiente'));
    }

    private function dashboardUsuario($user)
    {
        $areas               = collect();
        $totalUsuarios       = 0;
        $pendientes          = collect();
        $solicitudesMembresia = 0;
        $sinArea             = !$user->area_id;
        $solicitudPendiente  = $user->areaSolicitudPendiente;

        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes', 'solicitudesMembresia', 'sinArea', 'solicitudPendiente'));
    }
}
