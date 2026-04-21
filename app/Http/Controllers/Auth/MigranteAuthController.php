<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActividadLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MigranteAuthController extends Controller
{
    public function showLogin(): View
    {
        $migrantes = User::where('role_id', 5)
            ->where('status', 'alta')
            ->whereHas('certificados', fn($q) => $q->where('status', 'activo'))
            ->with('migrantePerfil')
            ->orderBy('name')
            ->get();

        return view('auth.acceso-migrante', compact('migrantes'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'llave'   => ['required', 'file', 'max:64'],
        ]);

        $user = User::find($request->user_id);

        if ($user->role_id !== 5 || $user->status !== 'alta') {
            return back()->with('error', 'Acceso no disponible para esta identidad.');
        }

        $cert = $user->certificados()->where('status', 'activo')->latest()->first();

        if (!$cert) {
            return back()->with('error', 'No hay un certificado activo para esta identidad. Contacte al personal.');
        }

        $pemContent = file_get_contents($request->file('llave')->path());
        $privateKey = openssl_pkey_get_private($pemContent);

        if (!$privateKey) {
            ActividadLog::registrar('acceso_fallido', $user, [
                'razon' => 'llave_invalida',
                'ip'    => $request->ip(),
            ]);
            return back()->with('error', 'El archivo de llave no es válido o está dañado.');
        }

        $details     = openssl_pkey_get_details($privateKey);
        $fingerprint = hash('sha256', $details['key']);

        if (!hash_equals($cert->fingerprint, $fingerprint)) {
            ActividadLog::registrar('acceso_fallido', $user, [
                'razon' => 'llave_no_corresponde',
                'ip'    => $request->ip(),
            ]);
            return back()->with('error', 'La llave no corresponde a la identidad seleccionada.');
        }

        ActividadLog::registrar('acceso_migrante', $user, [
            'fingerprint' => $fingerprint,
            'ip'          => $request->ip(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('migrante.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('migrante.login');
    }
}
