<?php

namespace App\Services;

use App\Models\Servicio;
use Illuminate\Database\Eloquent\Collection;

class ServicioService
{
    public function listarTodos(): Collection
    {
        return Servicio::all();
    }

    public function obtenerPorId(int $id): ?Servicio
    {
        return Servicio::find($id);
    }

    public function crear(array $datos): Servicio
    {
        return Servicio::create($datos);
    }

    public function actualizar(Servicio $servicio, array $datos): Servicio
    {
        $servicio->update($datos);
        return $servicio->fresh();
    }

    public function eliminar(Servicio $servicio): bool
    {
        return $servicio->delete();
    }
}