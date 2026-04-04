<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function approve(User $user)
    {
        $user->update([
            'status' => 'alta'
        ]);

        return back()->with('status', "¡Listo! Usuario {$user->name} aprobado exitosamente.");
    }
}