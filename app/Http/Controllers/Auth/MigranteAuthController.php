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
            ->with('migrantePerfil')
            ->orderBy('name')
            ->get();

        return view('auth.acceso-migrante', compact('migrantes'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id'  => ['required', 'integer', 'exists:users,id'],
            'password' => ['required', 'string'],
        ]);

        $user = User::find($request->user_id);

        if ($user->role_id !== 5 || $user->status !== 'alta') {
            return back()->with('error', 'Acceso no disponible para esta identidad.');
        }

        if (! \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            ActividadLog::registrar('acceso_fallido', $user, [
                'razon' => 'password_incorrecta',
                'ip'    => $request->ip(),
            ]);
            return back()->with('error', 'La contraseña no es correcta. Solicite al personal de Casa Monarca que le proporcione su contraseña.');
        }

        ActividadLog::registrar('acceso_migrante', $user, ['ip' => $request->ip()]);

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
