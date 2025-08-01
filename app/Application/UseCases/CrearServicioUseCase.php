<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CrearServicioDTO;
use App\Domain\Entities\ServicioEntity;
use App\Domain\Services\ServicioDomainService;

class CrearServicioUseCase
{
    public function __construct(
        private ServicioDomainService $domainService
    ) {}

    public function ejecutar(CrearServicioDTO $dto): ServicioEntity
    {
        try {
            // Delegar al domain service
            return $this->domainService->crear($dto->toArray());
            
        } catch (\DomainException $e) {
            // Convertir excepción de dominio a excepción de aplicación
            throw new \App\Application\Exceptions\ServicioApplicationException(
                $e->getMessage(),
                previous: $e
            );
        }
    }
}
