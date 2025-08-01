<?php

namespace App\Domain\Services;

use App\Domain\Entities\ServicioEntity;
use App\Domain\Ports\ServicioRepositoryPort;

class ServicioDomainService
{
    public function __construct(
        private ServicioRepositoryPort $repository
    ) {}

    public function obtenerTodos(): array
    {
        return $this->repository->obtenerTodos();
    }

    public function obtenerPorId(int $id): ?ServicioEntity
    {
        return $this->repository->obtenerPorId($id);
    }

    public function crear(array $datos): ServicioEntity
    {
        // 1. Crear entity del dominio
        $servicioEntity = ServicioEntity::create($datos);

        // 2. Validar reglas de negocio
        if (!$servicioEntity->esValido()) {
            throw new \DomainException('Servicio inválido');
        }

        // 3. Verificar unicidad del código (regla de negocio)
        if ($this->repository->existeCodigoServicio($servicioEntity->getCodigoServicio())) {
            throw new \DomainException('El código de servicio ya existe');
        }

        // 4. Delegar persistencia al adapter
        return $this->repository->crear($servicioEntity);
    }

    public function actualizar(int $id, array $datos): ServicioEntity
    {
        // 1. Verificar que existe
        $servicioExistente = $this->repository->obtenerPorId($id);
        if (!$servicioExistente) {
            throw new \DomainException('Servicio no encontrado');
        }

        // 2. Crear nueva entity con datos actualizados
        $servicioActualizado = ServicioEntity::create($datos);

        // 3. Validar reglas de negocio
        if (!$servicioActualizado->esValido()) {
            throw new \DomainException('Servicio inválido');
        }

        // 4. Delegar actualización al adapter
        return $this->repository->actualizar($id, $servicioActualizado);
    }

    public function eliminar(int $id): bool
    {
        // 1. Verificar que existe
        $servicio = $this->repository->obtenerPorId($id);
        if (!$servicio) {
            throw new \DomainException('Servicio no encontrado');
        }

        // 2. Aquí podrían ir reglas de negocio
        // Por ejemplo: no eliminar si está en paquetes activos

        // 3. Delegar eliminación al adapter
        return $this->repository->eliminar($id);
    }
}