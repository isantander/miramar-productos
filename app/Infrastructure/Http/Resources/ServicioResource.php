<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo_servicio' => $this->codigo_servicio,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'destino' => $this->destino,
            'fecha' => $this->fecha->format('Y-m-d'), // está casteada en el modelo así que aplico format directamente
            'precio' => $this->precio, 
            'creado_en' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
