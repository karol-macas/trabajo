<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {
        // Verifica si el usuario es supervisor y permite el acceso pero tambien al administrador
        if (Auth::user() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        
        if (Auth::user() && Auth::user()->empleado && Auth::user()->empleado->es_supervisor) {
            return $next($request);
        }

     

        abort(403, 'Acceso denegado.');
    }
}
