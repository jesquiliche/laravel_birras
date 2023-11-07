<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('v1/colores', App\Http\Controllers\Api\V1\ColorController::class);
Route::apiResource('v1/paises', App\Http\Controllers\Api\V1\PaisController::class);
Route::apiResource('v1/tipos', App\Http\Controllers\Api\V1\TipoController::class);
Route::apiResource('v1/graduaciones', App\Http\Controllers\Api\V1\GraduacionController::class);

Route::apiResource('v1/cervezas', App\Http\Controllers\Api\V1\CervezaController::class);