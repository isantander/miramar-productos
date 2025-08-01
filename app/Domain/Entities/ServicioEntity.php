<?php

namespace App\Domain\Entities;

class ServicioEntity
{
    public function __construct(
        private ?int $id,
        private string $codigoServicio,
        private string $nombre,
        private string $descripcion,
        private string $destino,
        private \DateTime $fecha,
        private float $precio,
        private ?\DateTime $createdAt = null,
        private ?\DateTime $updatedAt = null
    ) {}

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigoServicio(): string
    {
        return $this->codigoServicio;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getDestino(): string
    {
        return $this->destino;
    }

    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    // factory metodos
    public static function create(array $data): self
    {
        return new self(
            id: null, // Nuevo servicio
            codigoServicio: $data['codigo_servicio'],
            nombre: $data['nombre'],
            descripcion: $data['descripcion'],
            destino: $data['destino'],
            fecha: new \DateTime($data['fecha']),
            precio: (float) $data['precio']
        );
    }

    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            codigoServicio: $model->codigo_servicio,
            nombre: $model->nombre,
            descripcion: $model->descripcion,
            destino: $model->destino,
            fecha: $model->fecha->toDateTime(),
            precio: (float) $model->precio,
            createdAt: $model->created_at?->toDateTime(),
            updatedAt: $model->updated_at?->toDateTime()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codigo_servicio' => $this->codigoServicio,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'destino' => $this->destino,
            'fecha' => $this->fecha->format('Y-m-d'),
            'precio' => $this->precio,
        ];
    }

    // métodos de dominio (reglas de negocio)
    public function esValido(): bool
    {
        return !empty($this->codigoServicio) 
            && !empty($this->nombre)
            && $this->precio > 0;
    }

}