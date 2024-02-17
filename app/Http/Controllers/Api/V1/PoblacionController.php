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
    /**
     * @OA\Get(
     *     path="/api/v1/poblaciones",
     *     operationId="index",
     *     tags={"Poblaciones"},
     *     summary="Obtener todas las poblaciones",
     *     description="Devuelve todas las poblaciones ordenadas por nombre o filtradas por provincia si se proporciona.",
     *     @OA\Parameter(
     *         name="provincia",
     *         in="query",
     *         description="Código de la provincia para filtrar las poblaciones.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve todas las poblaciones ordenadas por nombre o filtradas por provincia si se proporciona.",
     *     )
     * )
     */

     public function index(Request $request)
     {
         $provincia = $request->input('provincia'); // Valor predeterminado es null si no se proporciona
     
         $query = Poblacion::orderBy('nombre');
     
         if ($provincia !== null) {
             $query->where('provincia_cod', $provincia);
         }
     
         return $query->get();
     }
}
