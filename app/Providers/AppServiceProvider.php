<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ServicioService;
use App\Services\PaqueteService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ServicioService::class);
        $this->app->singleton(PaqueteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
