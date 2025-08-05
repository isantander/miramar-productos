<?php

namespace App\Domain\Entities;

class ServicioEntity
{
    public function __construct(
        private ?int $id,
        private string $codigo,
        private string $nombre,
        private string $descripcion,
        private string $destino,
        private \DateTime $fecha,
        private float $costo,
        private ?\DateTime $createdAt = null,
        private ?\DateTime $updatedAt = null
    ) {}

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
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

    public function getCosto(): float
    {
        return $this->costo;
    }


    // factory metodos
    public static function create(array $data): self
    {
        //dd($data);
        return new self(
            id: null,
            codigo: $data['codigo'],
            nombre: $data['nombre'],
            descripcion: $data['descripcion'],
            destino: $data['destino'],
            fecha: new \DateTime($data['fecha']),
            costo: (float) $data['costo']
        );
    }

    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            codigo: $model->codigo,
            nombre: $model->nombre,
            descripcion: $model->descripcion,
            destino: $model->destino,
            fecha: $model->fecha->toDateTime(),
            costo: (float) $model->costo,
            createdAt: $model->created_at?->toDateTime(),
            updatedAt: $model->updated_at?->toDateTime()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'destino' => $this->destino,
            'fecha' => $this->fecha->format('Y-m-d'),
            'costo' => $this->costo,
        ];
    }

    // métodos de dominio - reglas de negocio
    public function esValido(): bool
    {
        return !empty($this->nombre)
            && !empty($this->codigo)
            && $this->costo > 0;
    }

}