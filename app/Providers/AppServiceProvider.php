<?php

namespace App\Providers;

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Los servicios del módulo se resuelven mediante el contenedor de Laravel.
    }

    public function boot(): void
    {
        // Livewire 3 usa componentes de página vía GET; esta macro permite
        // conservar Route::livewire sin sustituir el paquete instalado.
        if (! Route::hasMacro('livewire')) {
            Route::macro('livewire', function (string $uri, string $component): RoutingRoute {
                return Route::get($uri, $component);
            });
        }
    }
}
