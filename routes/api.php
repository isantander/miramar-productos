<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\PaqueteController;
use App\Infrastructure\Http\Controllers\ServicioHexagonalController;




/* arq tradicional, no versiono la api para cumplir el requerimiento del TP
*  GET /api/servicios      ← Sin versionado
*/
Route::apiResource('servicios', ServicioController::class);
Route::apiResource('paquetes', PaqueteController::class);


/* v2  arq hexagonal 
*  GET /api/v2/servicios  ← Versionado para Arquitectura Hexagonal
*/
Route::prefix('v2')->name('v2.')->group(function () {
    Route::apiResource('servicios', ServicioHexagonalController::class);
});