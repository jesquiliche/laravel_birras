<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Direccion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DireccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy', 'update', 'patch']);
    }

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
     *      security={{"bearerAuth": {}}},    
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos de la nueva dirección",
     *          @OA\JsonContent(
     *              @OA\Property(property="nombre", type="string", maxLength=100),
     *              @OA\Property(property="apellidos", type="string", maxLength=150),
     *              @OA\Property(property="calle", type="string", maxLength=150),
     *              @OA\Property(property="numero", type="string", maxLength=5),
     *              @OA\Property(property="poblacion", type="string", maxLength=5),
     *              @OA\Property(property="piso", type="string", maxLength=20),
     *              @OA\Property(property="puerta", type="string", maxLength=5),
     *              @OA\Property(property="provincia", type="string", maxLength=2),
     *              @OA\Property(property="telefono", type="string", maxLength=15),
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
     *                  @OA\Property(property="nombre", type="string"),
     *                  @OA\Property(property="apellidos", type="string"),
     *                  @OA\Property(property="calle", type="string"),
     *                  @OA\Property(property="numero", type="string"),
     *                  @OA\Property(property="poblacion", type="string"),
     *                  @OA\Property(property="piso", type="string"),
     *                  @OA\Property(property="puerta", type="string"),
     *                  @OA\Property(property="provincia", type="string"),
     *                  @OA\Property(property="telefono", type="string"),
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
             'nombre'=>'required|string|max:100',
             'apellidos'=>'required|string|max:150',
             'calle' => 'required|string|max:150',
             'numero' => 'required|string|max:5',
             'poblacion' => 'required|string|max:5',
             'piso'=>'required|string|max:20',
             'puerta'=>'required|string|max:5',
             'provincia' => 'required|string|max:2',
             'telefono'=>'required|string|max:15',
             'user_id' => 'required|exists:users,id',
         ]);
     
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
     
         // Verificar si la dirección ya existe para el usuario dado
         $direccion = Direccion::where('user_id', $request->user_id)->first();
     
         if ($direccion) {
             // Si la dirección existe, actualiza los datos
             $direccion->update($request->all());
             return response()->json(['message' => 'Dirección actualizada con éxito', 'direccion' => $direccion], 200);
         } else {
             // Si la dirección no existe, crea una nueva dirección
             $direccion = Direccion::create($request->all());
             return response()->json(['message' => 'Dirección creada con éxito', 'direccion' => $direccion], 201);
         }
     }
     

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *      path="/api/v1/direcciones/{user_id}",
     *      operationId="getDireccionById",
     *      tags={"Direcciones"},
     *      summary="Obtener una dirección por ID",
     *      description="Recupera una dirección específica por el id del usuario y la devuelve como una respuesta JSON",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del usuario  de la dirección a obtener",
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
     *                  @OA\Property(property="nombre", type="string"),
     *                  @OA\Property(property="apellidos", type="string"),
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
    public function show(string $user_id)
    {
        // Buscar la dirección por user_id
        $direccion = Direccion::where('user_id', $user_id)->first();
    
        // Verificar si se encontró la dirección
        if (!$direccion) {
            return response()->json(['message' => 'Dirección no encontrada'], 404);
        }
    
        // Retornar la dirección encontrada
        return response()->json($direccion);
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
     *      description="Elimina una dirección existente por su ID del usuario y devuelve una respuesta JSON",
     *      security={{"bearerAuth": {}}},     
     *      @OA\Parameter(
     *          name="user_id",
     *          description="ID del usuario de la dirección a eliminar",
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
    public function destroy(string $user_id)
    {
        // Buscar la dirección por user_id
        $direccion = Direccion::where('user_id', $user_id)->first();
    
        // Verificar si se encontró la dirección
        if ($direccion) {
            // Eliminar la dirección
            $direccion->delete();
            
            // Retornar una respuesta JSON indicando que la dirección ha sido eliminada con éxito
            return response()->json(['message' => 'Dirección eliminada con éxito'], 204);
        } else {
            // Retornar una respuesta JSON indicando que no se encontró la dirección
            return response()->json(['message' => 'Dirección no encontrada'], 404);
        }
    }

}
