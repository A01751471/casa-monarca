<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActividadLog;
use App\Models\MigrantePerfil;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MigranteRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.registro-migrante');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_atencion'         => ['required', 'date'],
            'nombre'                 => ['required', 'string', 'max:255'],
            'primer_apellido'        => ['required', 'string', 'max:255'],
            'segundo_apellido'       => ['nullable', 'string', 'max:255'],
            'telefono'               => ['nullable', 'string', 'max:30'],
            'genero'                 => ['required', 'string'],
            'pais_origen'            => ['required', 'string', 'max:255'],
            'departamento_estado'    => ['nullable', 'string', 'max:255'],
            'estado_civil'           => ['required', 'string'],
            'fecha_nacimiento'       => ['required', 'date', 'before:today'],
            'rango_edad'             => ['required', 'string'],
            'grupo_poblacion'        => ['required', 'string'],
            'motivo_salida'          => ['nullable', 'string', 'max:255'],
            'num_acompanantes'       => ['nullable', 'integer', 'min:0'],
            'integrantes_grupo'      => ['nullable', 'string'],
            'documentacion'          => ['nullable', 'string'],
            'necesidades_especiales' => ['nullable', 'string'],
            'destino_final'          => ['nullable', 'string', 'max:255'],
        ]);

        // Crear cuenta de usuario para el migrante (rol 5 = Migrante)
        $password   = Str::password(12); // contraseña temporal segura
        $nombreCompleto = trim($request->nombre . ' ' . $request->primer_apellido);

        $userMigrante = User::create([
            'name'     => $nombreCompleto,
            'email'    => 'migrante_' . Str::uuid() . '@casamonarca.local',
            'password' => Hash::make(Str::random(64)),
            'role_id'  => 5,
            'area_id'  => null,
            'status'   => 'pendiente',
        ]);

        $perfil = MigrantePerfil::create([
            'user_id'                => $userMigrante->id,
            'fecha_atencion'         => $request->fecha_atencion,
            'nombre'                 => $request->nombre,
            'primer_apellido'        => $request->primer_apellido,
            'segundo_apellido'       => $request->segundo_apellido,
            'telefono'               => $request->telefono,
            'genero'                 => $request->genero,
            'pais_origen'            => $request->pais_origen,
            'departamento_estado'    => $request->departamento_estado,
            'estado_civil'           => $request->estado_civil,
            'fecha_nacimiento'       => $request->fecha_nacimiento,
            'rango_edad'             => $request->rango_edad,
            'grupo_poblacion'        => $request->grupo_poblacion,
            'motivo_salida'          => $request->motivo_salida,
            'num_acompanantes'       => $request->num_acompanantes ?? 0,
            'integrantes_grupo'      => $request->integrantes_grupo,
            'documentacion'          => $request->documentacion,
            'necesidades_especiales' => $request->necesidades_especiales,
            'destino_final'          => $request->destino_final,
            'registrado_por'         => auth()->check() ? auth()->id() : null,
            'status'                 => 'activo',
        ]);

        ActividadLog::registrar('registró_migrante', $perfil, [
            'nombre'  => $nombreCompleto,
            'user_id' => $userMigrante->id,
        ]);

        session(['migrante_registrado_nombre' => $nombreCompleto]);

        return redirect()->route('migrante.registro.exitoso');
    }

    public function exitoso(): View
    {
        return view('auth.registro-migrante-exitoso');
    }
}
