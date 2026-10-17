<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $modulo, string $accion): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->estaActivo()) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Su cuenta se encuentra bloqueada.']);
        }

        if (!$user->tienePermiso($modulo, $accion)) {
            abort(403, "No tienes permiso para '{$accion}' en el módulo de {$modulo}.");
        }

        return $next($request);
    }
}
