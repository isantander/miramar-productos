<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioHexagonalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo_servicio' => $this->codigo_servicio,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'destino' => $this->destino,
            'fecha' => $this->fecha->format('Y-m-d'),
            'precio' => $this->precio,
        ];
    }
}
