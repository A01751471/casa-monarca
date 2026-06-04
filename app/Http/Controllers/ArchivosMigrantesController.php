<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArchivosMigrantesController extends Controller
{
    // ── Feed: grid of all migrants with their doc counts ─────────

    public function index(): View
    {
        Gate::authorize('puede-actualizar');

        $rolMigrante = Role::where('name', 'Migrante')->value('id') ?? 5;

        $migrantes = User::where('role_id', $rolMigrante)
            ->where('status', 'alta')
            ->withCount([
                'documentos as total_docs'   => fn($q) => $q->where('categoria', 'identidad'),
                'documentos as docs_sellados' => fn($q) => $q->where('categoria', 'identidad')
                                                             ->whereNotNull('sello_integridad'),
            ])
            ->with('migrantePerfil')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.archivos.index', compact('migrantes'));
    }

    // ── Show a migrant's identity documents with integrity status ─

    public function show(User $migrante): View
    {
        Gate::authorize('puede-actualizar');

        abort_if($migrante->role_id !== (Role::where('name', 'Migrante')->value('id') ?? 5), 403);

        $documentos = Documento::where('user_id', $migrante->id)
            ->deIdentidad()
            ->latest()
            ->get()
            ->map(function (Documento $doc) {
                $doc->sello_valido = $doc->selladoEsValido();
                return $doc;
            });

        return view('admin.archivos.migrante', compact('migrante', 'documentos'));
    }

    // ── Deep file integrity check (reads disk, returns JSON) ─────

    public function verificar(Documento $documento): JsonResponse
    {
        Gate::authorize('puede-actualizar');
        abort_if($documento->categoria !== 'identidad', 403);

        if (!Storage::disk('local')->exists($documento->ruta_storage)) {
            return response()->json(['status' => 'missing', 'mensaje' => 'Archivo no encontrado en storage.']);
        }

        $hashActual = hash_file('sha256', Storage::disk('local')->path($documento->ruta_storage));
        $hashCoincide = hash_equals($hashActual, $documento->hash_sha256 ?? '');
        $selladoValido = $documento->selladoEsValido();

        $status = match (true) {
            !$hashCoincide    => 'corrupto',
            !$selladoValido   => 'sin_sello',
            default           => 'integro',
        };

        return response()->json([
            'status'        => $status,
            'hash_guardado' => $documento->hash_sha256,
            'hash_actual'   => $hashActual,
            'coincide'      => $hashCoincide,
            'sellado'       => $selladoValido,
            'sellado_at'    => $documento->sellado_at?->format('d/m/Y H:i'),
        ]);
    }
}
