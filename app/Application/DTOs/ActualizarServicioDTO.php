<?php

namespace App\Application\DTOs;

class ActualizarServicioDTO
{
    public function __construct(
        public readonly string $codigoServicio,
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly string $destino,
        public readonly string $fecha,
        public readonly float $precio
    ) {}

    public static function fromRequest(array $requestData): self
    {
        return new self(
            codigoServicio: $requestData['codigo_servicio'],
            nombre: $requestData['nombre'],
            descripcion: $requestData['descripcion'],
            destino: $requestData['destino'],
            fecha: $requestData['fecha'],
            precio: (float) $requestData['precio']
        );
    }

    public function toArray(): array
    {
        return [
            'codigo_servicio' => $this->codigoServicio,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'destino' => $this->destino,
            'fecha' => $this->fecha,
            'precio' => $this->precio,
        ];
    }
}