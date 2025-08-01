<?php

namespace App\Application\UseCases;

use App\Domain\Services\ServicioDomainService;

class EliminarServicioUseCase
{
    public function __construct(
        private ServicioDomainService $domainService
    ) {}

    public function ejecutar(int $id): bool
    {
        try {
            return $this->domainService->eliminar($id);
            
        } catch (\DomainException $e) {
            throw new \App\Application\Exceptions\ServicioApplicationException(
                $e->getMessage(),
                previous: $e
            );
        }
    }
}