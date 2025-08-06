<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\PaqueteController;
use App\Infrastructure\Http\Controllers\ServicioHexagonalController;

/*
|--------------------------------------------------------------------------
| API Routes - Microservicio Productos
|--------------------------------------------------------------------------
*/

// Rutas públicas con autenticación por API Key
Route::middleware(['api.key:frontend,admin'])->group(function () {
    
    /* Arquitectura tradicional - Sin versionado para cumplir requerimiento del TP
    *  GET /api/servicios      ← Sin versionado
    */
    Route::apiResource('servicios', ServicioController::class);
    Route::apiResource('paquetes', PaqueteController::class);

    /* v2 - Arquitectura hexagonal 
    *  GET /api/v2/servicios  ← Versionado para Arquitectura Hexagonal
    */
    Route::prefix('v2')->name('v2.')->group(function () {
        Route::apiResource('servicios', ServicioHexagonalController::class);
    });
});

// Rutas internas (comunicación entre microservicios)
Route::middleware(['internal.service'])->prefix('internal')->group(function () {
    // Endpoints optimizados para comunicación interna
    Route::get('servicios/{id}', [ServicioController::class, 'show'])->name('internal.servicios.show');
    Route::get('paquetes/{id}', [PaqueteController::class, 'show'])->name('internal.paquetes.show');
    
    // Endpoint para validar múltiples productos (batch)
    Route::post('productos/validate', function(\Illuminate\Http\Request $request) {
        $items = $request->validate([
            'items' => 'required|array',
            'items.*.producto_id' => 'required|integer',
            'items.*.tipo' => 'required|string|in:servicio,paquete'
        ]);

        $results = [];
        foreach ($items['items'] as $item) {
            try {
                $controller = $item['tipo'] === 'servicio' ? 
                    new ServicioController() : new PaqueteController();
                
                $producto = $controller->show($item['producto_id']);
                $results[] = [
                    'producto_id' => $item['producto_id'],
                    'tipo' => $item['tipo'],
                    'found' => true,
                    'data' => $producto->getData(true)
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'producto_id' => $item['producto_id'],
                    'tipo' => $item['tipo'],
                    'found' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json(['results' => $results]);
    })->name('internal.productos.validate');
});

// Health check sin autenticación
Route::get('health', function () {
    return response()->json([
        'service' => 'miramar-productos',
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'authentication' => 'api-key'
    ]);
})->name('health');