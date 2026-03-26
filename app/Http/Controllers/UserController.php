<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //lista de solicitudes de usuarios
    public function index()
    {
        $users = User::where('status', 'pending')->get();
        $areas = Area::all();
        return view('admin.users.index', compact('users', 'areas'));
    }
    //aprobar y asignar área a usuario

    public function approve(Request $request, User $user)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'role_requested' => 'required|string|in:migrante,voluntario,admin',
        ]);
        $user->update([
            'status' => 'active',
            'area_id' => $request->area_id,
            'role_requested' => $request->role_requested, // Esto evita el error de SQL
        ]);

        return redirect()->route('users.index')->with('success', "Usuario {$user->name} configurado correctamente.");
    }
}