<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if (!$user->empresa_id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Seu usuario nao possui empresa vinculada.',
            ]);
        }

        $lojas = $user->lojas()
            ->where('empresa_id', $user->empresa_id)
            ->orderBy('nome')
            ->get();

        $lojaId = (int) $request->session()->get('loja_id');
        $lojaValida = $lojas->firstWhere('id', $lojaId);

        if (!$lojaValida && $lojas->isNotEmpty()) {
            $request->session()->put('loja_id', (int) $lojas->first()->id);
            $lojaValida = $lojas->first();
        }

        if (!$lojaValida) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Seu usuario nao possui acesso a nenhuma loja.',
            ]);
        }

        return $next($request);
    }
}
