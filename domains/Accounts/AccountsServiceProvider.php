<?php

namespace Domains\Accounts;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Dusterio\LumenPassport\LumenPassport;

class AccountsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        LumenPassport::allowMultipleTokens();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/Resources/Views', 'accounts');
        $this->loadConfig();
        $this->bootRoutes();
        $this->routeMiddleware();
    }

    private function bootRoutes(): void
    {
        Route::group(
            [
                'prefix' => 'accounts',
                'as' => 'accounts',
            ],
            fn () => $this->loadRoutesFrom(__DIR__ . '/routes.php')
        );
    }

    private function loadConfig(): void
    {
        $this->app->configure('accounts');
        $this->app->configure('auth');
    }

    private function routeMiddleware()
    {
        $this->app->routeMiddleware(
            [
                'unAuthenticate' => \Domains\Accounts\Middleware\UnAuthenticate::class,
                'throttle' => \GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware::class,
            ]
        );
    }
}
