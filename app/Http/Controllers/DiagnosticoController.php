<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Area;
use App\Models\Certificado;
use App\Models\Documento;
use App\Models\Expediente;
use App\Models\MigrantePerfil;
use App\Models\Role;
use App\Models\Solicitud;
use App\Models\User;

class DiagnosticoController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        if (auth()->user()->role_id != 1) {
            abort(403);
        }

        return view('admin.diagnostico', [
            // Conteos generales
            'totalUsuarios'    => User::count(),
            'totalActivos'     => User::where('status', 'alta')->count(),
            'totalPendientes'  => User::where('status', 'pendiente')->count(),
            'totalSuspendidos' => User::where('status', 'revocacion')->count(),
            'totalBaja'        => User::where('status', 'baja')->count(),

            // Por rol
            'porRol' => Role::withCount('users')->get(),

            // Por área
            'porArea' => Area::withCount('users')->get(),

            // Certificados
            'certsActivos'   => Certificado::where('status', 'activo')->count(),
            'certsRevocados' => Certificado::where('status', 'revocado')->count(),
            'ultimosCerts'   => Certificado::with('user')->latest('emitido_at')->take(5)->get(),

            // Expedientes
            'expedientesSinAsignar' => Expediente::where('status', 'sin_asignar')->count(),
            'expedientesEnProceso'  => Expediente::where('status', 'en_proceso')->count(),
            'expedientesTerminados' => Expediente::where('status', 'terminado')->count(),

            // Migrantes
            'totalMigrantes'  => MigrantePerfil::count(),
            'ultimosMigrantes'=> MigrantePerfil::latest()->take(5)->get(),

            // Documentos y firmas
            'totalDocumentos' => Documento::count(),
            'totalSolicitudes'=> Solicitud::count(),

            // Log de actividad reciente
            'ultimasActividades' => ActividadLog::latest()->take(10)->get(),

            // Tablas del sistema
            'tablasOk' => $this->verificarTablas(),
        ]);
    }

    private function verificarTablas(): array
    {
        $tablas = [
            'users', 'roles', 'areas', 'certificados', 'expedientes',
            'documentos', 'firmas', 'solicitudes', 'actividad_log', 'migrante_perfiles',
        ];

        $resultado = [];
        foreach ($tablas as $tabla) {
            try {
                \DB::table($tabla)->count();
                $resultado[$tabla] = true;
            } catch (\Exception $e) {
                $resultado[$tabla] = false;
            }
        }
        return $resultado;
    }
}
