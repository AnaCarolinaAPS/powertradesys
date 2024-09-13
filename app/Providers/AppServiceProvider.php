<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PacotesPendentes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

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
        // Cache o número de Pacotes pendentes por 60 segundos
        View::composer('layouts.body.sidebar', function ($view) {
            // Obter o cliente do usuário logado
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                $pendingPacotesCount = Cache::remember('pending_pacotes_count', 60, function() {
                    return PacotesPendentes::where('status', '!=', 'encontrado')->count();
                }); 
            } elseif ($user->hasRole('guest')) {
                $pendingPacotesCount = Cache::remember('pending_pacotes_count'. 'guest', 60, function() {
                    return 0;
                }); 
            } else {
                $clienteId = $user->cliente->id;
                $pendingPacotesCount = Cache::remember('pending_pacotes_count'. $clienteId, 60, function() use ($clienteId) {
                    return PacotesPendentes::where('cliente_id', $clienteId)
                                            ->where('status', '!=', 'encontrado')->count();
                });    
            }
            $view->with('pendingPacotesCount', $pendingPacotesCount);
        });
    }
}
