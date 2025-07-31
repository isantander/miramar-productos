<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\PaqueteStoreRequest;
use App\Http\Requests\PaqueteUpdateRequest;
use App\Http\Resources\PaqueteResource;
use App\Services\PaqueteService;

class PaqueteController extends Controller
{
    public function __construct(
        private PaqueteService $paqueteService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $paquetes = $this->paqueteService->listarTodos();
        return PaqueteResource::collection($paquetes);        
    }

    
    public function store(PaqueteStoreRequest $request): PaqueteResource
    {
        $paquete = $this->paqueteService->crear($request->validated());
        return new PaqueteResource($paquete);
    }

    
    public function show(string $id): PaqueteResource|JsonResponse
    {
        $paquete = $this->paqueteService->obtenerPorId($id);
        
        if (!$paquete) {
            return response()->json(['error' => 'Paquete no encontrado'], 404);
        }
        
        return new PaqueteResource($paquete);    
    }
    
    public function update(PaqueteUpdateRequest $request, string $id): PaqueteResource|JsonResponse
    {
        $paquete = $this->paqueteService->obtenerPorId($id);
        
        if (!$paquete) {
            return response()->json(['error' => 'Paquete no encontrado'], 404);
        }
        
        $paqueteActualizado = $this->paqueteService->actualizar($paquete, $request->validated());
        return new PaqueteResource($paqueteActualizado);
    }


    public function destroy(string $id): JsonResponse
    {
        $paquete = $this->paqueteService->obtenerPorId($id);
        
        if (!$paquete) {
            return response()->json(['error' => 'Paquete no encontrado'], 404);
        }
        
        $this->paqueteService->eliminar($paquete);
        return response()->json(null, 204);
    }
}
