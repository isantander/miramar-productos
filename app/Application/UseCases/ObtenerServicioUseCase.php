<?php

namespace App\Application\UseCases;

use App\Domain\Entities\ServicioEntity;
use App\Domain\Services\ServicioDomainService;

class ObtenerServicioUseCase
{
    public function __construct(
        private ServicioDomainService $domainService
    ) {}

    public function ejecutar(int $id): ?ServicioEntity
    {
        return $this->domainService->obtenerPorId($id);
    }

    public function ejecutarTodos(): array
    {
        return $this->domainService->obtenerTodos();
    }
}
