<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Traemos todas las áreas y contamos cuántos usuarios tiene cada una
        $areas = Area::withCount('users')->get();
        // 2. Contamos el total de colaboradores activos
        $totalUsuarios = User::where('status', 'alta')->count();
        // 3. Traemos las solicitudes de acceso pendientes
        $pendientes = User::where('status', 'pendiente')->get();


        // 3. Enviamos los datos a la vista
        return view('dashboard', compact('areas', 'totalUsuarios', 'pendientes'));
    }
}
