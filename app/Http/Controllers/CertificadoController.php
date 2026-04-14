<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Certificado;

class CertificadoController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        if (auth()->user()->role_id != 1) {
            abort(403);
        }

        $certificados = Certificado::with(['user', 'emisor'])
            ->orderByDesc('emitido_at')
            ->get();

        return view('admin.certificados.index', compact('certificados'));
    }

    public function revoke(Certificado $certificado): \Illuminate\Http\RedirectResponse
    {
        if (auth()->user()->role_id != 1) {
            abort(403);
        }

        $certificado->update([
            'status'      => 'revocado',
            'revocado_at' => now(),
        ]);

        ActividadLog::registrar('revocó_certificado', $certificado, [
            'fingerprint' => $certificado->fingerprint,
            'usuario'     => $certificado->user?->name ?? '(eliminado)',
        ]);

        return back()->with('status', 'Certificado revocado correctamente.');
    }
}
