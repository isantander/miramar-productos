<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicioStoreRequest;
use App\Http\Requests\ServicioUpdateRequest;
use App\Http\Resources\ServicioResource;
use App\Models\Servicio;
use App\Services\ServicioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServicioController extends Controller
{
    // inyectar el ServicioService
    public function __construct(
        private ServicioService $servicioService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $servicios = $this->servicioService->listarTodos();
        return ServicioResource::collection($servicios);
    }

    /**
     * Lista un servicio especificado por ID.
     *
     * @param  int  $id
     * @return ServicioResource|JsonResponse
     */
    public function show(int $id): ServicioResource|JsonResponse
    {
        $servicio = $this->servicioService->obtenerPorId($id);
        
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
        
        return new ServicioResource($servicio);
    }

    /**
     * Almacena un nuevo servicio en la base de datos.
     * @param  ServicioStoreRequest  $request
     * @return ServicioResource 
     */
    public function store(ServicioStoreRequest $request): ServicioResource
    {
        $servicio = $this->servicioService->crear($request->validated());
        return new ServicioResource($servicio);
    }
    
    /**
     * Actualiza un servicio existente en la base de datos.
     *
     * @param  ServicioUpdateRequest  $request
     * @param  int  $id
     * @return ServicioResource|JsonResponse
     */
    public function update(ServicioUpdateRequest $request, int $id): ServicioResource|JsonResponse
    {
        $servicio = $this->servicioService->obtenerPorId($id);
        
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
        
        $servicioActualizado = $this->servicioService->actualizar($servicio, $request->validated());
        return new ServicioResource($servicioActualizado);
    }
 
    /**
     * Borra un servicio especificado por ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $servicio = $this->servicioService->obtenerPorId($id);
        
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
        
        $this->servicioService->eliminar($servicio);
        return response()->json(null, 204);
    }
}
