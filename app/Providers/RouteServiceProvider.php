<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Cesta k domovskej route po prihlásení.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Definuj všetky routy v aplikácii.
     */
    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            // API routy – s prefixom /api a middleware 'api'
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web routy – napríklad pre testovanie welcome správy alebo login
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
