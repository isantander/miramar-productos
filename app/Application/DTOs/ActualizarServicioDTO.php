<?php

namespace App\Application\DTOs;

class ActualizarServicioDTO
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly string $destino,
        public readonly string $fecha,
        public readonly float $costo
    ) {}

    public static function fromRequest(array $requestData): self
    {
        return new self(
            codigo: $requestData['codigo'],
            nombre: $requestData['nombre'],
            descripcion: $requestData['descripcion'],
            destino: $requestData['destino'],
            fecha: $requestData['fecha'],
            costo: (float) $requestData['costo']
        );
    }

    public function toArray(): array
    {
        return [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'destino' => $this->destino,
            'fecha' => $this->fecha,
            'costo' => $this->costo,
        ];
    }
}