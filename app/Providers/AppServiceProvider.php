<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ServicioService;
use App\Services\PaqueteService;
use App\Domain\Services\ServicioDomainService;
use App\Domain\Ports\ServicioRepositoryPort;
use App\Infrastructure\Repositories\EloquentServicioRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Port → Adapter
        $this->app->bind(
            ServicioRepositoryPort::class,
            EloquentServicioRepository::class // Acá se define el adapter concreto, pudiendo pasar a MongoDB, Redis, etc, sin tener que cambiar el código del dominio
        );
        

        // Registrar Domain Service
        $this->app->singleton(ServicioDomainService::class);
        
        $this->app->singleton(ServicioService::class);
        $this->app->singleton(PaqueteService::class);

        $this->app->singleton(\App\Application\UseCases\CrearServicioUseCase::class);
        $this->app->singleton(\App\Application\UseCases\ObtenerServicioUseCase::class);
        $this->app->singleton(\App\Application\UseCases\ActualizarServicioUseCase::class);
        $this->app->singleton(\App\Application\UseCases\EliminarServicioUseCase::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
