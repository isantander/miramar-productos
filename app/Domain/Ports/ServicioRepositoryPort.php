<?php

namespace App\Domain\Ports;

use App\Domain\Entities\ServicioEntity;

interface ServicioRepositoryPort
{
    /**
     * Obtener todos los servicios
     */
    public function obtenerTodos(): array;

    /**
     * Obtener servicio por ID
     */
    public function obtenerPorId(int $id): ?ServicioEntity;

    /**
     * Crear nuevo servicio
     */
    public function crear(ServicioEntity $servicio): ServicioEntity;

    /**
     * Actualizar servicio existente
     */
    public function actualizar(int $id, ServicioEntity $servicio): ServicioEntity;

    /**
     * Eliminar servicio
     */
    public function eliminar(int $id): bool;

    /**
     * Verificar si existe un código de servicio
     */
    public function existeCodigo(string $codigo): bool;
}