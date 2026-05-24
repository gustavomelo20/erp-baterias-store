<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $user = Auth::user();

            if (!$user) {
                $view->with([
                    'tenantEmpresa' => null,
                    'tenantLojas' => collect(),
                    'tenantLojaAtual' => null,
                ]);

                return;
            }

            $lojas = $user->lojas()
                ->where('empresa_id', $user->empresa_id)
                ->orderBy('nome')
                ->get();

            $lojaAtual = $lojas->firstWhere('id', (int) session('loja_id')) ?? $lojas->first();

            $view->with([
                'tenantEmpresa' => $user->empresa,
                'tenantLojas' => $lojas,
                'tenantLojaAtual' => $lojaAtual,
            ]);
        });
    }
}
