<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            // Usuário não autenticado, redirecionar ou retornar erro
            return redirect('/');
        }
        
        $user = auth()->user();

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                // O usuário tem a role necessária, permitir acesso à rota
                return $next($request);
            }
        }

        // O usuário não tem a role necessária, redirecionar ou retornar erro
        return redirect()->route('pagamentos.index')->with('errorMessage', 'Você não tem permissão para acessar essa página.');
    }

}
