
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\ColorController;
use App\Http\Controllers\Api\V1\PaisController;
use App\Http\Controllers\Api\V1\TipoController;
use App\Http\Controllers\Api\V1\GraduacionController;
use App\Http\Controllers\Api\V1\CervezaController;
use App\Http\Controllers\Api\V1\SystemController; // Importar el controlador de direcciones
use App\Http\Controllers\Api\V1\PoblacionController;
use App\Http\Controllers\Api\V1\ProvinciaController;
use App\Http\Controllers\Api\V1\DireccionController;
use App\Http\Controllers\Api\V1\OrdenController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Rutas de recursos API existentes
Route::apiResource('v1/colores', ColorController::class);
Route::apiResource('v1/paises', PaisController::class);
Route::apiResource('v1/tipos', TipoController::class);
Route::apiResource('v1/graduaciones', GraduacionController::class);

// Rutas personalizadas API existentes
Route::get('v1/cervezas',[CervezaController::class,'index']);
Route::get('v1/cervezas/{id}',[CervezaController::class,'show']);
Route::get('v1/consultaCervezasPorPais',[SystemController::class,'consultaCervezasPorPais']);
Route::get('v1/consultaCervezasPorTipo',[SystemController::class,'consultaCervezasPorTipo']);
Route::get('v1/consultaCervezasPorColores',[SystemController::class,'consultaCervezasColores']);
Route::get('v1/consultaCervezasPorGraduaciones',[SystemController::class,'consultaCervezasGraduaciones']);
Route::get('v1/stockPorPais',[SystemController::class,'stockPorPais']);
Route::get('v1/consultaTablas',[SystemController::class,'consultaTablas']);
Route::get('v1/consultaTablas2',[SystemController::class,'consultaTablas2']);
Route::get('v1/consultaBD',[SystemController::class,'consultaBD']);
Route::put('v1/cervezas/{id}',[CervezaController::class,'update']);
Route::patch('v1/cervezas/{id}',[CervezaController::class,'patch']);
Route::post('v1/cervezas',[CervezaController::class,'store']);
Route::delete('v1/cervezas/{id}',[CervezaController::class,'destroy']);

// Rutas del recurso de direcciones
Route::apiResource('v1/direcciones', DireccionController::class);
Route::apiResource('v1/provincias', ProvinciaController::class);
Route::apiResource('v1/poblaciones', PoblacionController::class);

Route::apiResource('v1/ordenes', OrdenController::class);
Route::post('v1/pagarorden/{id}',[OrdenController::class,'pagarOrden']);