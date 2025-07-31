<?php

namespace App\Services;

use App\Models\Paquete;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class PaqueteService
{
    public function listarTodos(): Collection
    {
        return Paquete::with('servicios')->get();
    }

    public function obtenerPorId(int $id): ?Paquete
    {
        return Paquete::with('servicios')->find($id);
    }    

    public function crear(array $datos): Paquete
    {
        return DB::transaction(function () use ($datos) {

            // primero crear el paquete
            $paquete = Paquete::create([]);
            // después asociar los sevicios
            $paquete->servicios()->attach($datos['servicios']);

            // recargar
            return $paquete->load('servicios');
        });
    }    


    public function actualizar(Paquete $paquete, array $datos): Paquete
    {
        return DB::transaction(function () use ($paquete, $datos) {

            // primero crear el paquete
            $paquete->update([]);

            // syn -> sincroniza los servicios borrando los viejos y agregando los nuevos
            $paquete->servicios()->sync($datos['servicios']);

            // recargar relaciones
            return $paquete->load('servicios');
        });
    }

    public function eliminar(Paquete $paquete): bool
    {
        return DB::transaction(function () use ($paquete) {
            /* Observaciones Técnicas:
                - Al eliminar un paquete, se desasocian automáticamente todos los servicios relacionados
                - Se recomienda implementar confirmación en el frontend antes de llamar a este endpoint
                - El sistema maneja las restricciones de foreign key internamente
            */
            $paquete->servicios()->detach();
            return $paquete->delete();
        });
    }
}