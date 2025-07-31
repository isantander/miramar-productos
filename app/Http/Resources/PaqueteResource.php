<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaqueteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'servicios' => ServicioResource::collection($this->whenLoaded('servicios')),
            'precio_calculado' => $this->precio_calculado, // accessor automático definido en el modelo paquete
            'cantidad_servicios' => $this->servicios->count(),
            'creado_en' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }    
}
