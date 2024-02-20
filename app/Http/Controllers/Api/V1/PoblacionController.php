<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poblacion;

class PoblacionController extends Controller
{
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Poblacion A"),
     *                 @OA\Property(property="codigo", type="string", example="ABCDE"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
     *         ),
     *     ),
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

    /**
     * @OA\Get(
     *     path="/api/v1/poblaciones/{codigo}",
     *     operationId="show",
     *     tags={"Poblaciones"},
     *     summary="Obtener una población por su código",
     *     description="Recupera una población de la base de datos por su código y la devuelve como una respuesta JSON.",
     *     @OA\Parameter(
     *         name="codigo",
     *         in="path",
     *         required=true,
     *         description="Código de la población.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Población encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Poblacion A"),
     *             @OA\Property(property="codigo", type="string", example="ABCDE"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Población no encontrada",
     *     )
     * )
     */
    public function show(string $codigo)
    {
        $poblacion = Poblacion::where('codigo', '=', $codigo)->first();
        
        if (!$poblacion) {
            return response()->json(['error' => 'Población no encontrada'], 404);
        }
        
        return $poblacion;
    }
}
