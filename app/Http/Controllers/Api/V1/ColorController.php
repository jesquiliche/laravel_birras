<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color; // Asegúrate de importar el modelo Color
use Illuminate\Support\Facades\Validator;

 
class ColorController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * Método: index
     * Ruta asociada: GET /colors
     * Descripción: Este método muestra una lista de recursos (en este caso, colores).
     */
    /**
     * @OA\Get(
     *      path="/api/v1/colores",
     *      operationId="getColores",
     *      tags={"Colores"},
     *      summary="Obtener todos los colores",
     *      description="Recupera todos los colores de la base de datos y los devuelve como una respuesta JSON",
     *      @OA\Response(
     *          response=200,
     *          description="Lista de colores",
     *          @OA\JsonContent(
     *              @OA\Property(property="colores", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nombre", type="string", example="Rojo"),
     *                      @OA\Property(property="codigo", type="string", example="#FF0000"),
     *                  ),
     *              ),
     *          ),
     *      ),
     * )
     */

    public function index()
    {
        // Recupera todos los colores desde la base de datos y los devuelve como una respuesta JSON
        $colores = Color::all();
        return response()->json(['colores' => $colores]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * Método: create
     * Ruta asociada: GET /colors/create
     * Descripción: Este método muestra el formulario para crear un nuevo recurso (color).
     */
    /**
     * @OA\Post(
     *      path="/api/v1/colores",
     *      operationId="createColor",
     *      tags={"Colores"},
     *      summary="Crear un nuevo color",
     *      description="Crea un nuevo color utilizando los datos proporcionados en la solicitud y lo devuelve como una respuesta JSON",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos del nuevo color",
     *          @OA\JsonContent(
     *              @OA\Property(property="nombre", type="string", example="Azul"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Color creado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Color creado con éxito"),
     *              @OA\Property(property="color", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="nombre", type="string", example="Azul"),
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
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="nombre", type="array", @OA\Items(type="string")),
     *              ),
     *          ),
     *      ),
     * )
     */

    public function store(Request $request)
    {
        // Validación de los datos del nuevo color (por ejemplo, nombre, código de color).
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:colores'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Debe estar configurado fillable en el modelo para 
        //utilizar inserción masiva

        $color = Color::create($request->all());

        // Retornar una respuesta JSON que confirma la creación exitosa del color.
        return response()->json(['message' => 'Color creado con éxito', 'color' => $color], 201);
    }

    /**
     * Display the specified resource.
     *
     * Método: show
     * Ruta asociada: GET /colors/{id}
     * Descripción: Este método muestra un recurso (color) específico identificado por su ID.
     */
    public function show(string $id)
    {
        // Buscar el color por su ID en la base de datos y retornarlo como una respuesta JSON.
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color no encontrado'], 404);
        }


        return response()->json(['color' => $color]);
    }


    /**
     * Update the specified resource in storage.
     *
     * Método: update
     * Ruta asociada: PUT/PATCH /colors/{id}
     * Descripción: Este método actualiza un recurso (color) específico identificado por su ID en el almacenamiento.
     */
    /**
     * @OA\Put(
     *      path="/api/v1/colores/{id}",
     *      operationId="updateColor",
     *      tags={"Colores"},
     *      summary="Actualizar un color existente",
     *      description="Actualiza un color existente utilizando los datos proporcionados en la solicitud y lo devuelve como una respuesta JSON",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del color a actualizar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos actualizados del color",
     *          @OA\JsonContent(
     *              @OA\Property(property="nombre", type="string", example="Verde"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Color actualizado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Color actualizado con éxito"),
     *              @OA\Property(property="color", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="nombre", type="string", example="Verde"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Color no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Color no encontrado"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="nombre", type="array", @OA\Items(type="string")),
     *              ),
     *          ),
     *      ),
     * )
     */

    public function update(Request $request, string $id)
    {
        // Validación de los datos actualizados del color.
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:colores'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Los datos proporcionados no son válidos', 'errors' => $validator->errors()], 422);
        }

        // Buscar el color por su ID en la base de datos.
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color no encontrado'], 404);
        }

        // Actualizar los datos del color con los datos validados.
        $color->update($request->all());

        // Retornar una respuesta JSON que confirma la actualización exitosa del color.
        return response()->json(['message' => 'Color actualizado con éxito', 'color' => $color]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Método: destroy
     * Ruta asociada: DELETE /colors/{id}
     * Descripción: Este método elimina un recurso (color) específico identificado por su ID del almacenamiento.
     */
    /**
     * @OA\Delete(
     *      path="/api/v1/colores/{id}",
     *      operationId="deleteColor",
     *      tags={"Colores"},
     *      summary="Eliminar un color existente",
     *      description="Elimina un color existente por su ID y lo devuelve como una respuesta JSON",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del color a eliminar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Color eliminado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Color eliminado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Color no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Color no encontrado"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="No se pudo borrar el color, tiene cervezas relacionadas",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se pudo borrar el color, tiene cervezas relacionadas"),
     *          ),
     *      ),
     * )
     */

    public function destroy(string $id)
    {
        // Buscar el color por su ID en la base de datos.
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color no encontrado'], 404);
        }

        if ($color->cervezas()->exists()) {
            return response()->json(['message' => 'No se pudo borrar el color, tiene cervezas relacionadas'], 400);
        }

        // Eliminar el color de la base de datos.
        $color->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del color.
        return response()->json(['message' => 'Color eliminado con éxito']);
    }
}
