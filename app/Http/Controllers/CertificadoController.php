<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Certificado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    private function soloAdmin(): void
    {
        if (auth()->user()->role_id != 1) {
            abort(403, 'Acceso denegado.');
        }
    }

    public function index(Request $request): \Illuminate\View\View
    {
        $this->soloAdmin();

        // Marcar como vencidos los que ya pasaron su fecha antes de mostrar
        Certificado::where('status', 'activo')
            ->where('vence_at', '<', now())
            ->update(['status' => 'vencido']);

        $query = Certificado::with(['user.area', 'user.role', 'emisor'])
            ->orderByDesc('emitido_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificados = $query->paginate(20)->withQueryString();

        $stats = [
            'activos'   => Certificado::where('status', 'activo')->count(),
            'revocados' => Certificado::where('status', 'revocado')->count(),
            'vencidos'  => Certificado::where('status', 'vencido')->count(),
        ];

        return view('admin.certificados.index', compact('certificados', 'stats'));
    }

    public function destroy(Certificado $certificado): RedirectResponse
    {
        $this->soloAdmin();

        ActividadLog::registrar('eliminó_certificado', $certificado, [
            'fingerprint' => $certificado->fingerprint,
            'usuario'     => $certificado->user?->name ?? '(eliminado)',
        ]);

        $certificado->delete();

        return back()->with('status', 'Certificado eliminado permanentemente.');
    }
}
