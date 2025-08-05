<?php

namespace App\Infrastructure\Http\Controllers; 

use App\Http\Controllers\Controller;
use App\Infrastructure\Http\Requests\ServicioStoreRequest;
use App\Infrastructure\Http\Requests\ServicioUpdateRequest;
use App\Infrastructure\Http\Resources\ServicioHexagonalResource;
use App\Application\UseCases\CrearServicioUseCase;
use App\Application\UseCases\ObtenerServicioUseCase;
use App\Application\UseCases\ActualizarServicioUseCase;
use App\Application\UseCases\EliminarServicioUseCase;
use App\Application\DTOs\CrearServicioDTO;
use App\Application\DTOs\ActualizarServicioDTO;
use App\Application\Exceptions\ServicioApplicationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServicioHexagonalController extends Controller
{
    public function __construct(
        private CrearServicioUseCase $crearUseCase,
        private ObtenerServicioUseCase $obtenerUseCase,
        private ActualizarServicioUseCase $actualizarUseCase,
        private EliminarServicioUseCase $eliminarUseCase
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $servicios = $this->obtenerUseCase->ejecutarTodos();
        
        // Convertir Entities a Resources
        $resources = collect($servicios)->map(fn($entity) => 
            new ServicioHexagonalResource($this->entityToStdClass($entity))
        );
        
        return ServicioHexagonalResource::collection($resources);
    }

    public function show(int $id): ServicioHexagonalResource|JsonResponse
    {
        try {
            $servicio = $this->obtenerUseCase->ejecutar($id);
            
            if (!$servicio) {
                return response()->json(['error' => 'Servicio no encontrado'], 404);
            }
            
            return new ServicioHexagonalResource($this->entityToStdClass($servicio));
            
        } catch (ServicioApplicationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(ServicioStoreRequest $request): ServicioHexagonalResource|JsonResponse
    {
        try {
            $dto = CrearServicioDTO::fromRequest($request->validated());
            $servicio = $this->crearUseCase->ejecutar($dto);
            
            return new ServicioHexagonalResource($this->entityToStdClass($servicio));
            
        } catch (ServicioApplicationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(ServicioUpdateRequest $request, int $id): ServicioHexagonalResource|JsonResponse
    {
        try {
            $dto = ActualizarServicioDTO::fromRequest($request->validated());
            $servicio = $this->actualizarUseCase->ejecutar($id, $dto);
            
            return new ServicioHexagonalResource($this->entityToStdClass($servicio));
            
        } catch (ServicioApplicationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $resultado = $this->eliminarUseCase->ejecutar($id);
            
            if ($resultado) {
                return response()->json(null, 204);
            }
            
            return response()->json(['error' => 'No se pudo eliminar'], 400);
            
        } catch (ServicioApplicationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Helper: Convertir Entity a objeto para Resource
     */
    private function entityToStdClass($entity): \stdClass
    {
        $obj = new \stdClass();
        $obj->id = $entity->getId();
        $obj->codigo = $entity->getCodigo();
        $obj->nombre = $entity->getNombre();
        $obj->descripcion = $entity->getDescripcion();
        $obj->destino = $entity->getDestino();
        $obj->fecha = $entity->getFecha();
        $obj->costo = $entity->getCosto();
        
        return $obj;
    }
}
