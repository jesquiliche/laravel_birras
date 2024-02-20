<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Provincia;
use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /*public function __construct()
    {
        $this->middleware('auth:api');
    }*/

    /**
     * @OA\Get(
     *      path="/api/v1/provincias",
     *      operationId="getProvincias",
     *      tags={"Provincias"},
     *      summary="Obtener todas las provincias",
     *      description="Recupera todas las provincias de la base de datos y las devuelve como una respuesta JSON ordenadas por nombre.",
     *      @OA\Response(
     *          response=200,
     *          description="Lista de provincias ordenadas por nombre",
     *          @OA\JsonContent(
     *              @OA\Property(property="provincias", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nombre", type="string", example="Provincia A"),
     *                      @OA\Property(property="created_at", type="string", format="date-time"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time"),
     *                  ),
     *              ),
     *          ),
     *      ),
     * )
     */
    public function index()
    {
        // Recupera todas las provincias desde la base de datos y las devuelve como una respuesta JSON ordenadas por nombre
        return Provincia::orderBy('nombre')->get();
    }

    public function show(string $codigo)
    {
        // Recupera todas las provincias desde la base de datos y las devuelve como una respuesta JSON ordenadas por nombre
        return Provincia::where('codigo','=',$codigo)->get();
    }
    
}
