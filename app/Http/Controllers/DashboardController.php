<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Certificado;
use App\Models\Expediente;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role_id == 1) {
            return $this->dashboardAdmin($user);
        }

        if ($user->role_id == 2) {
            return $this->dashboardCoordinador($user);
        }

        return $this->dashboardOperativo($user);
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
        $areas           = Area::where('id', $user->area_id)->withCount('users')->get();
        $totalUsuarios   = User::where('area_id', $user->area_id)->where('status', 'alta')->count();
        $pendientes      = User::where('area_id', $user->area_id)->where('status', 'pendiente')->get();

        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes'));
    }

    private function dashboardOperativo($user)
    {
        $areas         = collect();
        $totalUsuarios = 0;
        $pendientes    = collect();

        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes'));
    }
}
