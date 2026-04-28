<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Area;
use App\Models\Role;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $areas = Area::all();
        // Excluir Administrador (1) y Migrante (5) del formulario de registro normal
        $roles = Role::whereNotIn('id', [1, 5])->get();
        return view('auth.register', compact('areas', 'roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $esExterno = $request->input('tipo_participacion') === 'externo';

        $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'           => ['required', 'confirmed', Rules\Password::defaults()],
            'tipo_participacion' => ['required', 'in:interno,externo'],
            // role_id solo es obligatorio para personal interno
            'role_id'  => $esExterno ? ['nullable'] : ['required', 'exists:roles,id', 'in:2,3'],
            'area_id'  => $esExterno ? ['nullable'] : ['required_if:role_id,2,3', 'nullable', 'exists:areas,id'],
        ]);

        // Agente externo siempre queda en role 4 (Usuario), sin área
        $roleId  = $esExterno ? 4 : (int) $request->role_id;
        $areaId  = $esExterno ? null : $request->area_id;

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $roleId,
            'area_id'  => $areaId,
            'status'   => 'pendiente',
        ]);

        event(new Registered($user));

        return redirect()->route('auth.espera-aprobacion');
    }
}
