<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActividadLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PemAuthController extends Controller
{
    /**
     * Issue a random nonce that the client will sign with their private key.
     * Stored in session so we can verify it once and discard it.
     */
    public function challenge(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)
                    ->where('status', 'alta')
                    ->with('certificadoActivo')
                    ->first();

        if (! $user || ! $user->certificadoActivo) {
            // Return generic error to avoid email enumeration
            return response()->json(['error' => 'Sin certificado activo.'], 422);
        }

        $nonce = Str::random(64);
        session(['pem_auth_nonce' => $nonce, 'pem_auth_email' => $request->email]);

        return response()->json(['nonce' => $nonce]);
    }

    /**
     * Verify that the uploaded PEM file produced a valid signature over the nonce.
     * Uses the public key stored in certificados (we never touch the private key).
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email'     => ['required', 'email'],
            'signature' => ['required', 'string'],
        ]);

        $nonce = session('pem_auth_nonce');
        $email = session('pem_auth_email');

        // Guard: nonce must exist and belong to this email
        if (! $nonce || $email !== $request->email) {
            return back()->withErrors(['pem' => 'Sesión de autenticación inválida. Intenta de nuevo.']);
        }

        // Invalidate nonce immediately (one-time use)
        session()->forget(['pem_auth_nonce', 'pem_auth_email']);

        $user = User::where('email', $request->email)
                    ->where('status', 'alta')
                    ->with('certificadoActivo')
                    ->first();

        if (! $user || ! $user->certificadoActivo) {
            return back()->withErrors(['pem' => 'No existe un certificado activo para este correo.']);
        }

        $cert    = $user->certificadoActivo;
        $pubKey  = openssl_pkey_get_public($cert->public_key);

        if (! $pubKey) {
            return back()->withErrors(['pem' => 'No se pudo leer la llave pública almacenada.']);
        }

        $signatureBytes = base64_decode($request->signature, true);

        if ($signatureBytes === false) {
            return back()->withErrors(['pem' => 'Firma inválida (formato base64 incorrecto).']);
        }

        $result = openssl_verify($nonce, $signatureBytes, $pubKey, OPENSSL_ALGO_SHA256);

        if ($result !== 1) {
            ActividadLog::registrar('fallo_pem_login', $user, ['email' => $request->email]);
            return back()->withErrors(['pem' => 'La firma no es válida. Verifica que estás usando la llave correcta.']);
        }

        // Signature valid — log in
        auth()->login($user, false);
        $request->session()->regenerate();

        ActividadLog::registrar('ingresó_con_pem', $user, [
            'fingerprint' => $cert->fingerprint,
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
