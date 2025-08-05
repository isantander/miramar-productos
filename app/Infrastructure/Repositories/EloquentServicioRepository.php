<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\ServicioEntity;
use App\Domain\Ports\ServicioRepositoryPort;
use App\Models\Servicio;

class EloquentServicioRepository implements ServicioRepositoryPort
{
    public function obtenerTodos(): array
    {
        $modelos = Servicio::all();
        
        // Convertir modelos Eloquent a Entities del dominio
        return $modelos->map(fn($modelo) => ServicioEntity::fromModel($modelo))
                      ->toArray();
    }

    public function obtenerPorId(int $id): ?ServicioEntity
    {
        $modelo = Servicio::find($id);
        
        if (!$modelo) {
            return null;
        }
        
        return ServicioEntity::fromModel($modelo);
    }

    public function crear(ServicioEntity $servicio): ServicioEntity
    {
        // Convertir Entity a array para Eloquent
        $datos = $servicio->toArray();
        unset($datos['id']); // Quitar ID para nuevo registro
        
        $modelo = Servicio::create($datos);
        
        // Retornar Entity actualizada con ID
        return ServicioEntity::fromModel($modelo);
    }

    public function actualizar(int $id, ServicioEntity $servicio): ServicioEntity
    {
        $modelo = Servicio::findOrFail($id);
        
        // Convertir Entity a array para Eloquent
        $datos = $servicio->toArray();
        unset($datos['id']); // No actualizar ID
        
        $modelo->update($datos);
        
        // Retornar Entity actualizada
        return ServicioEntity::fromModel($modelo->fresh());
    }

    public function eliminar(int $id): bool
    {
        $modelo = Servicio::findOrFail($id);
        return $modelo->delete();
    }

    public function existeCodigo(string $codigo): bool
    {
        return Servicio::where('codigo', $codigo)->exists();
    }
}