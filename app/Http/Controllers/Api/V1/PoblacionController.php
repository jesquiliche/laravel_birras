<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poblacion;

class PoblacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * @OA\Get(
         *     path="/api/v1/poblaciones",
         *     operationId="index",
         *     tags={"Poblaciones"},
         *     summary="Obtener todas las poblaciones",
         *     description="Devuelve todas las poblaciones ordenadas por nombre.",
         *     @OA\Response(
         *         response=200,
         *         description="Devuelve todas las poblaciones ordenadas por nombre.",
         *     )
         * )
         */
        return Poblacion::orderBy('nombre')->get();
    }

}
    