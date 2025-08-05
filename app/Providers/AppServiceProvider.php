<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ServicioService;
use App\Services\PaqueteService;
use App\Domain\Services\ServicioDomainService;
use App\Domain\Ports\ServicioRepositoryPort;
use App\Infrastructure\Repositories\EloquentServicioRepository;
use App\Models\Servicio;
use App\Observers\ServicioObserver;


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
            EloquentServicioRepository::class // acá se define el adapter concreto, pudiendo pasar a MongoDB, Redis, etc, sin tener que cambiar el código del dominio
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
     //   Servicio::observe(ServicioObserver::class); 
     /*
     Lo había planeado para dar un foramto específico a los códigos de servicio, pero no estaba conteplado en el TP 
     */
    }
}
