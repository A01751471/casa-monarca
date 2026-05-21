<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentoIdentidadController extends Controller
{
    // ── Migrant: view and upload their own identity docs ─────────

    public function index(): View
    {
        $user = auth()->user();
        abort_if($user->role_id !== 5, 403);

        $documentos = Documento::where('user_id', $user->id)
                               ->deIdentidad()
                               ->latest()
                               ->get();

        $etiquetas = Documento::etiquetasIdentidad();

        return view('migrante.documentos.index', compact('documentos', 'etiquetas'));
    }

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

        Documento::create([
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

        return back()->with('status', "Documento «{$etiqueta}» subido correctamente.");
    }

    public function destroy(Documento $documento): RedirectResponse
    {
        $user = auth()->user();

        // Migrant can only delete their own; admin can delete any identity doc
        $esAdmin    = Gate::check('puede-eliminar');
        $esPropiet  = $documento->user_id === $user->id && $documento->categoria === 'identidad';

        abort_if(! $esAdmin && ! $esPropiet, 403);

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

        // For case docs, staff with access to that expediente may download
        if ($documento->categoria === 'expediente') {
            abort_if(! $esStaff, 403);
        } else {
            abort_if(! $esStaff && ! $esPropiet, 403);
        }

        abort_unless(Storage::disk('local')->exists($documento->ruta_storage), 404);

        return Storage::disk('local')->download(
            $documento->ruta_storage,
            $documento->nombre . '.' . $documento->tipo
        );
    }

    // ── Admin: list identity docs for a specific migrant ─────────

    public function porMigrante(User $usuario): View
    {
        Gate::authorize('puede-eliminar');

        $documentos = Documento::where('user_id', $usuario->id)
                               ->deIdentidad()
                               ->latest()
                               ->get();

        return view('admin.users.documentos-migrante', compact('usuario', 'documentos'));
    }
}
