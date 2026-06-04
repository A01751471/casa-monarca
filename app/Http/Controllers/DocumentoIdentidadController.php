<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Documento;
use App\Models\SolicitudRectificacion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentoIdentidadController extends Controller
{
    // ── Migrant: view their identity docs ────────────────────────

    public function index(): View
    {
        $user = auth()->user();
        abort_if($user->role_id !== 5, 403);

        $documentos = Documento::where('user_id', $user->id)
                               ->deIdentidad()
                               ->latest()
                               ->get();

        $solicitudesActivas = SolicitudRectificacion::where('solicitante_id', $user->id)
            ->whereNotIn('status', ['aprobada', 'rechazada'])
            ->get()
            ->keyBy('documento_id');

        $etiquetas = Documento::etiquetasIdentidad();

        return view('migrante.documentos.index', compact('documentos', 'etiquetas', 'solicitudesActivas'));
    }

    // ── Migrant: upload a new identity doc ───────────────────────

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        abort_if($user->role_id !== 5, 403);

        $request->validate([
            'archivo'  => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
            'etiqueta' => ['required', 'string', 'max:100'],
            'nombre'   => ['nullable', 'string', 'max:255'],
        ]);

        $file     = $request->file('archivo');
        $etiqueta = $request->etiqueta;
        $nombre   = $request->filled('nombre')
                    ? $request->nombre
                    : $etiqueta . ' — ' . $user->name;

        $ruta = $file->store("identidad/{$user->id}", 'local');
        $hash = hash_file('sha256', $file->getRealPath());

        $documento = Documento::create([
            'user_id'      => $user->id,
            'subido_por'   => $user->id,
            'categoria'    => 'identidad',
            'etiqueta'     => $etiqueta,
            'nombre'       => $nombre,
            'tipo'         => $file->getClientOriginalExtension(),
            'ruta_storage' => $ruta,
            'hash_sha256'  => $hash,
        ]);

        ActividadLog::registrar('subió_documento_identidad', $user, [
            'etiqueta' => $etiqueta,
            'nombre'   => $nombre,
        ]);

        // Señal para que la vista muestre el paso de confirmación/sellado
        return back()->with('doc_pendiente_sello', $documento->id)
                     ->with('doc_pendiente_nombre', $documento->nombre);
    }

    // ── Migrant: apply integrity seal after confirmation ─────────

    public function sellar(Documento $documento): RedirectResponse
    {
        $user = auth()->user();
        abort_if($user->role_id !== 5, 403);
        abort_if($documento->user_id !== $user->id || $documento->categoria !== 'identidad', 403);
        abort_if($documento->sello_integridad !== null, 422, 'Este documento ya está sellado.');

        $documento->update([
            'sello_integridad' => hash_hmac('sha256', $documento->hash_sha256, config('app.key')),
            'sellado_at'       => now(),
        ]);

        ActividadLog::registrar('selló_documento', $user, [
            'nombre'   => $documento->nombre,
            'etiqueta' => $documento->etiqueta,
            'hash'     => substr($documento->hash_sha256, 0, 16) . '…',
        ]);

        return back()->with('status', "Sello de integridad aplicado a «{$documento->nombre}».");
    }

    // ── Admin: delete identity doc directly ──────────────────────

    public function destroy(Documento $documento): RedirectResponse
    {
        Gate::authorize('puede-eliminar');
        abort_if($documento->categoria !== 'identidad', 403);

        Storage::disk('local')->delete($documento->ruta_storage);
        $documento->delete();

        return back()->with('status', 'Documento eliminado.');
    }

    // ── Secure download (staff + migrant owner) ──────────────────

    public function download(Documento $documento): mixed
    {
        $user      = auth()->user();
        $esStaff   = in_array($user->role_id, [1, 2, 3, 4]);
        $esPropiet = $documento->user_id === $user->id;

        if ($documento->categoria === 'expediente') {
            if (!$esStaff) {
                // Migrante solo puede descargar docs de su propio expediente que ya fueron aprobados
                $perfil   = $user->migrantePerfil;
                $esMiCaso = $perfil && $documento->expediente?->migrante_perfil_id === $perfil->id;
                abort_if(!$esMiCaso || !$documento->visible_migrante, 403);
            }
        } else {
            abort_if(!$esStaff && !$esPropiet, 403);
        }

        abort_unless(Storage::disk('local')->exists($documento->ruta_storage), 404);

        // Log de descarga para documentos de identidad (solo cuando el staff descarga)
        if ($esStaff && $documento->categoria === 'identidad') {
            ActividadLog::registrar('descargó_documento_identidad', $user, [
                'documento'  => $documento->nombre,
                'etiqueta'   => $documento->etiqueta,
                'propietario'=> $documento->propietario?->name,
                'hash'       => substr($documento->hash_sha256, 0, 16) . '…',
            ]);
        }

        return Storage::disk('local')->download(
            $documento->ruta_storage,
            $documento->nombre . '.' . $documento->tipo
        );
    }
}
