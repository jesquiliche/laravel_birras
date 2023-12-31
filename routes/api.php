<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::apiResource('v1/colores', App\Http\Controllers\Api\V1\ColorController::class);
Route::apiResource('v1/paises', App\Http\Controllers\Api\V1\PaisController::class);
Route::apiResource('v1/tipos', App\Http\Controllers\Api\V1\TipoController::class);
Route::apiResource('v1/graduaciones', App\Http\Controllers\Api\V1\GraduacionController::class);

Route::get('v1/cervezas',[App\Http\Controllers\Api\V1\CervezaController::class,'index']);
Route::get('v1/cervezas/{id}',[App\Http\Controllers\Api\V1\CervezaController::class,'show']);
Route::get('v1/consultaCervezasPorPais',[App\Http\Controllers\Api\V1\SystemController::class,'consultaCervezasPorPais']);
Route::get('v1/consultaCervezasPorTipo',[App\Http\Controllers\Api\V1\SystemController::class,'consultaCervezasPorTipo']);
Route::get('v1/consultaTablas',[App\Http\Controllers\Api\V1\SystemController::class,'consultaTablas']);
Route::get('v1/consultaDB',[App\Http\Controllers\Api\V1\SystemController::class,'consultaBD']);

Route::put('v1/cervezas/{id}',[App\Http\Controllers\Api\V1\CervezaController::class,'update']);
Route::patch('v1/cervezas/{id}',[App\Http\Controllers\Api\V1\CervezaController::class,'patch']);
Route::post('v1/cervezas',[App\Http\Controllers\Api\V1\CervezaController::class,'store']);
Route::delete('v1/cervezas/{id}',[App\Http\Controllers\Api\V1\CervezaController::class,'destroy']);