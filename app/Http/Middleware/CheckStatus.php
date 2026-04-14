<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->status !== 'alta') {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta aún está pendiente de aprobación o ha sido suspendida.',
            ]);
        }
        return $next($request);
    }
}
