<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\User;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        if ($user->role_id == 1) {
            // 1. Traemos todas las áreas y contamos cuántos usuarios tiene cada una
            $areas = Area::withCount('users')->get();
            // 2. Contamos el total de colaboradores activos
            $totalUsuarios = User::where('status', 'alta')->count();
            // 3. Traemos las solicitudes de acceso pendientes
            return view('dashboard', compact('areas', 'totalUsuarios'));
        }
        elseif ($user->role_id == 2) {
            // 1. Traemos el área del coordinador con el conteo de usuarios
            $areas = Area::where('id', $user->area_id)->withCount('users')->get();
            // 2. Contamos los colaboradores activos en su área
            $totalUsuarios = User::where('area_id', $user->area_id)->where('status', 'alta')->count();
            // 3. Traemos las solicitudes de acceso pendientes de su área
            $pendientes = User::where('area_id', $user->area_id)->where('status', 'pendiente')->get();
        }
        else {
            // Para Operativos, no mostramos nada (o podríamos mostrar un mensaje)
            $areas = collect(); // Colección vacía
            $totalUsuarios = 0;
            $pendientes = collect(); // Colección vacía
        }
        // 3. Enviamos los datos a la vista
        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes'));
    }
}
