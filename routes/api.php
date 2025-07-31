<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\PaqueteController;

Route::apiResource('servicios', ServicioController::class);
Route::apiResource('paquetes', PaqueteController::class);
