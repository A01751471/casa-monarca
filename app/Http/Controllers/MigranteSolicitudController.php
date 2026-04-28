<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Area;
use App\Models\Expediente;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MigranteSolicitudController extends Controller
{
    private function soloMigrante(): void
    {
        if (auth()->user()->role_id !== 5) {
            abort(403);
        }
    }

    public function dashboard(): View
    {
        $this->soloMigrante();

        $user   = auth()->user()->load('migrantePerfil');
        $perfil = $user->migrantePerfil;

        $solicitudesRecientes = Solicitud::where('user_id', $user->id)
            ->with(['area', 'expediente'])
            ->latest()
            ->take(5)
            ->get();

        return view('migrante.dashboard', compact('user', 'perfil', 'solicitudesRecientes'));
    }

    public function index(): View
    {
        $this->soloMigrante();

        $solicitudes = Solicitud::where('user_id', auth()->id())
            ->with(['area', 'expediente'])
            ->latest()
            ->paginate(15);

        return view('migrante.solicitudes.index', compact('solicitudes'));
    }

    public function create(): View
    {
        $this->soloMigrante();

        $perfil = auth()->user()->migrantePerfil;

        if (!$perfil) {
            abort(403, 'No hay perfil de migrante asociado a esta cuenta.');
        }

        // Excluir área de Tecnologías de Información (id 6) — interna, no atiende solicitudes de migrantes
        $areas = Area::where('id', '!=', 6)->orderBy('nombre')->get();

        return view('migrante.solicitudes.create', compact('areas', 'perfil'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->soloMigrante();

        $request->validate([
            'area_id'     => ['required', 'exists:areas,id'],
            'tipo'        => ['required', 'in:documento,proceso,apoyo,informacion,otro'],
            'descripcion' => ['required', 'string', 'max:1000'],
        ]);

        $user   = auth()->user();
        $perfil = $user->migrantePerfil;

        if (!$perfil) {
            abort(403);
        }

        $solicitud = Solicitud::create([
            'migrante_perfil_id' => $perfil->id,
            'user_id'            => $user->id,
            'area_id'            => $request->area_id,
            'tipo'               => $request->tipo,
            'descripcion'        => $request->descripcion,
            'status'             => 'pendiente',
        ]);

        ActividadLog::registrar('levantó_solicitud', $solicitud, [
            'migrante' => $user->name,
            'area_id'  => $request->area_id,
            'tipo'     => $request->tipo,
        ]);

        return redirect()->route('migrante.solicitudes.index')
            ->with('status', 'Su solicitud fue enviada. El equipo de Casa Monarca la atenderá pronto.');
    }

    // El migrante puede ver los documentos de su caso
    public function verDocumentos(Expediente $expediente): \Illuminate\View\View
    {
        $this->soloMigrante();

        $perfil = auth()->user()->migrantePerfil;

        if (!$perfil || $expediente->migrante_perfil_id !== $perfil->id) {
            abort(403, 'No tienes acceso a este expediente.');
        }

        $expediente->load(['documentos.autor', 'area']);

        return view('migrante.caso.documentos', compact('expediente'));
    }

    // El migrante puede marcar su caso como resuelto desde su portal
    public function resolver(Solicitud $solicitud): RedirectResponse
    {
        $this->soloMigrante();

        if ($solicitud->user_id !== auth()->id()) {
            abort(403);
        }

        if ($solicitud->status !== 'en_proceso') {
            return back()->with('status', 'Solo se puede marcar como resuelta una solicitud en proceso.');
        }

        $solicitud->update(['status' => 'completada']);

        if ($solicitud->expediente_id) {
            Expediente::where('id', $solicitud->expediente_id)->update([
                'status'      => 'terminado',
                'resuelto_por' => auth()->id(),
                'resuelto_at' => now(),
            ]);
        }

        ActividadLog::registrar('migrante_resolvió_solicitud', $solicitud, [
            'migrante'    => auth()->user()->name,
            'solicitud_id' => $solicitud->id,
        ]);

        return back()->with('status', 'Su solicitud fue marcada como resuelta. Gracias.');
    }
}
