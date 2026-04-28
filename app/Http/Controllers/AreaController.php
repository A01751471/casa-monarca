<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AreaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (Gate::allows('puede-eliminar')) {
            // Admin ve todas las áreas con conteo de usuarios
            $areas = Area::with(['users' => fn($q) => $q->with('role')])->get();
            return view('admin.areas.index', compact('areas'));
        }

        if ($user->role_id === 2 && $user->area_id) {
            // Coordinador ve solo su propia área
            $area = Area::with(['users' => fn($q) => $q->with('role')])->findOrFail($user->area_id);
            return view('admin.areas.show', compact('area'));
        }

        if (in_array($user->role_id, [3, 4]) && $user->area_id) {
            // Operativo/Usuario ve su área en modo lectura
            $area = Area::with(['users' => fn($q) => $q->with('role')])->findOrFail($user->area_id);
            return view('admin.areas.show', compact('area'));
        }

        abort(403);
    }
}
