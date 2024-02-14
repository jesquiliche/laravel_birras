<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Direccion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   /**
 * Display a listing of the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 * 
 * @OA\Get(
 *      path="/api/v1/direcciones",
 *      operationId="getDirecciones",
 *      tags={"Direcciones"},
 *      summary="Obtener todas las direcciones",
 *      description="Recupera todas las direcciones de la base de datos y las devuelve como una respuesta JSON",
 *      @OA\Parameter(
 *          name="user_id",
 *          description="ID del usuario para filtrar direcciones (opcional)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="integer"),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Lista de direcciones",
 *          @OA\JsonContent(
 *              @OA\Property(property="direcciones", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="calle", type="string"),
 *                      @OA\Property(property="numero", type="string"),
 *                      @OA\Property(property="poblacion_id", type="integer"),
 *                      @OA\Property(property="provincia_id", type="integer"),
 *                      @OA\Property(property="user_id", type="integer"),
 *                      @OA\Property(property="created_at", type="string", format="date-time"),
 *                      @OA\Property(property="updated_at", type="string", format="date-time"),
 *                  ),
 *              ),
 *          ),
 *      ),
 * )
 */
public function index(Request $request)
{
    $userId = $request->input('user_id');
    
    if ($userId) {
        $direcciones = Direccion::where('user_id', $userId)->get();
    } else {
        $direcciones = Direccion::all();
    }
    
    return response()->json(['direcciones' => $direcciones]);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *      path="/api/v1/direcciones",
     *      operationId="createDireccion",
     *      tags={"Direcciones"},
     *      summary="Crear una nueva dirección",
     *      description="Crea una nueva dirección utilizando los datos proporcionados en la solicitud y la devuelve como una respuesta JSON",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos de la nueva dirección",
     *          @OA\JsonContent(
     *              @OA\Property(property="calle", type="string"),
     *              @OA\Property(property="numero", type="string"),
     *              @OA\Property(property="poblacion_id", type="integer"),
     *              @OA\Property(property="provincia_id", type="integer"),
     *              @OA\Property(property="user_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Dirección creada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Dirección creada con éxito"),
     *              @OA\Property(property="direccion", type="object",
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="calle", type="string"),
     *                  @OA\Property(property="numero", type="string"),
     *                  @OA\Property(property="poblacion_id", type="integer"),
     *                  @OA\Property(property="provincia_id", type="integer"),
     *                  @OA\Property(property="user_id", type="integer"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *              @OA\Property(property="errors", type="object"),
     *          ),
     *      ),
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'calle' => 'required|string',
            'numero' => 'required|string',
            'poblacion_id' => 'required|exists:poblaciones,id',
            'provincia_id' => 'required|exists:provincias,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $direccion = Direccion::create($request->all());
        return response()->json(['message' => 'Dirección creada con éxito', 'direccion' => $direccion], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *      path="/api/v1/direcciones/{id}",
     *      operationId="getDireccionById",
     *      tags={"Direcciones"},
     *      summary="Obtener una dirección por ID",
     *      description="Recupera una dirección específica por su ID y la devuelve como una respuesta JSON",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID de la dirección a obtener",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Dirección recuperada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="direccion", type="object",
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="calle", type="string"),
     *                  @OA\Property(property="numero", type="string"),
     *                  @OA\Property(property="poblacion_id", type="integer"),
     *                  @OA\Property(property="provincia_id", type="integer"),
     *                  @OA\Property(property="user_id", type="integer"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Dirección no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Dirección no encontrada"),
     *          ),
     *      ),
     * )
     */
    public function show($id)
    {
        $direccion = Direccion::find($id);

        if (!$direccion) {
            return response()->json(['message' => 'Dirección no encontrada'], 404);
        }

        return response()->json(['direccion' => $direccion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     * 
     * @OA\Put(
     *      path="/api/v1/direcciones/{id}",
     *      operationId="updateDireccion",
     *      tags={"Direcciones"},
     *      summary="Actualizar una dirección existente",
     *      description="Actualiza una dirección existente utilizando los datos proporcionados en la solicitud y la devuelve como una respuesta JSON",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID de la dirección a actualizar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos actualizados de la dirección",
     *          @OA\JsonContent(
     *              @OA\Property(property="calle", type="string"),
     *              @OA\Property(property="numero", type="string"),
     *              @OA\Property(property="poblacion_id", type="integer"),
     *              @OA\Property(property="provincia_id", type="integer"),
     *              @OA\Property(property="user_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Dirección actualizada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Dirección actualizada con éxito"),
     *              @OA\Property(property="direccion", type="object",
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="calle", type="string"),
     *                  @OA\Property(property="numero", type="string"),
     *                  @OA\Property(property="poblacion_id", type="integer"),
     *                  @OA\Property(property="provincia_id", type="integer"),
     *                  @OA\Property(property="user_id", type="integer"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *              @OA\Property(property="errors", type="object"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Dirección no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Dirección no encontrada"),
     *          ),
     *      ),
     * )
     */
    public function update(Request $request, Direccion $direccion)
    {
        $validator = Validator::make($request->all(), [
            'calle' => 'string',
            'numero' => 'string',
            'poblacion_id' => 'exists:poblaciones,id',
            'provincia_id' => 'exists:provincias,id',
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $direccion->update($request->all());
        return response()->json(['message' => 'Dirección actualizada con éxito', 'direccion' => $direccion], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     * 
     * @OA\Delete(
     *      path="/api/v1/direcciones/{id}",
     *      operationId="deleteDireccion",
     *      tags={"Direcciones"},
     *      summary="Eliminar una dirección existente",
     *      description="Elimina una dirección existente por su ID y devuelve una respuesta JSON",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID de la dirección a eliminar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Dirección eliminada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Dirección eliminada con éxito"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Dirección no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Dirección no encontrada"),
     *          ),
     *      ),
     * )
     */
    public function destroy(Direccion $direccion)
    {
        $direccion->delete();
        return response()->json(['message' => 'Dirección eliminada con éxito'], 204);
    }
}
