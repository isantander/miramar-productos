<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ActualizarServicioDTO;
use App\Domain\Entities\ServicioEntity;
use App\Domain\Services\ServicioDomainService;

class ActualizarServicioUseCase
{
    public function __construct(
        private ServicioDomainService $domainService
    ) {}

    public function ejecutar(int $id, ActualizarServicioDTO $dto): ServicioEntity
    {
        try {
            return $this->domainService->actualizar($id, $dto->toArray());
            
        } catch (\DomainException $e) {
            throw new \App\Application\Exceptions\ServicioApplicationException(
                $e->getMessage(),
                previous: $e
            );
        }
    }
}