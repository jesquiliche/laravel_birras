<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Graduacion;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Schema(
 *     schema="Graduacion",
 *     type="object",
 *     title="Graduacion",
 *     properties={
 *         @OA\Property(property="id", type="integer", format="int64"),
 *         @OA\Property(property="nombre", type="string"),
 *     }
 * )
 */


class GraduacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * Método: index
     * Ruta asociada: GET /tipos
     * Descripción: Este método muestra una lista de recursos (en este caso, tipoes).
     */

    /**
     * @OA\Get(
     *      path="/api/v1/graduaciones",
     *      operationId="getGraduaciones",
     *      tags={"Graduaciones"},
     *      summary="Obtener todas las graduaciones",
     *      description="Recupera todas las graduaciones desde la base de datos y las retorna como una respuesta JSON.",
     *      @OA\Response(
     *          response=200,
     *          description="Lista de graduaciones",
     *          @OA\JsonContent(
     *              @OA\Property(property="graduaciones", type="array", @OA\Items(ref="#/components/schemas/Graduacion")),
     *          ),
     *      ),
     * )
     */


    public function index()
    {
        // Recuperar todos los tipos desde la base de datos y retornarlos como una respuesta JSON
        $graduaciones = Graduacion::all();
        return response()->json(['graduaciones' => $graduaciones]);
    }


    /**
     * @OA\Post(
     *      path="/api/v1/graduaciones",
     *      operationId="storeGraduacion",
     *      summary="Crear una nueva graduación",
     *      tags={"Graduaciones"},
     *      description="Crea una nueva graduación con los datos proporcionados en la solicitud y la retorna como una respuesta JSON.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos de la nueva graduación",
     *          @OA\JsonContent(
     *              required={"nombre"},
     *              @OA\Property(property="nombre", type="string", maxLength=150, description="Nombre de la nueva graduación"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Graduación creada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Graduación creado con éxito"),
     *              @OA\Property(property="graduacion", type="object", ref="#/components/schemas/Graduacion"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="El nombre ya está en uso."),
     *          ),
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request)
    {
        // Validación de los datos del nuevo tipo (por ejemplo, nombre, código de tipo).
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150|unique:graduaciones'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Debe estar configurado fillable en el modelo para 
        //utilizar inserción masiva
        $graduacion = Graduacion::create($request->all());

        // Retornar una respuesta JSON que confirma la creación exitosa del tipo.
        return response()->json(['message' => 'Graduación creada con éxito', 'graduacion' => $graduacion], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/graduaciones/{id}",
     *      operationId="getGraduacionById",
     *      tags={"Graduaciones"},
     *      summary="Obtener información de una graduación específica",
     *      description="Recupera la información de una graduación específica identificada por su ID y la retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID de la graduación",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Información de la graduación",
     *          @OA\JsonContent(
     *              @OA\Property(property="Graduacion", ref="#/components/schemas/Graduacion"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Graduación no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Graduación no encontrado"),
     *          ),
     *      ),
     * )
     */

    public function show(string $id)
    {
        // Buscar el tipo por su ID en la base de datos y retornarlo como una respuesta JSON.
        $graduacion = Graduacion::find($id);

        if (!$graduacion) {
            return response()->json(['message' => 'Graduación no encontrado'], 404);
        }


        return response()->json(['Graducación' => $graduacion]);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/graduaciones/{id}",
     *      operationId="updateGraduacion",
     *      tags={"Graduaciones"},
     *      summary="Actualizar una graduación existente",
     *      description="Actualiza una graduación existente identificada por su ID con los datos proporcionados en la solicitud y la retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID de la graduación a actualizar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos actualizados de la graduación",
     *          @OA\JsonContent(
     *              required={"nombre"},
     *              @OA\Property(property="nombre", type="string", maxLength=150, description="Nuevo nombre de la graduación"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Graduación actualizada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Graduación actualizado con éxito"),
     *              @OA\Property(property="graduacion", ref="#/components/schemas/Graduacion"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Graduación no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Graduación no encontrado"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="El nombre ya está en uso."),
     *          ),
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */

    public function update(Request $request, string $id)
    {
        // Validación de los datos actualizados del tipo.
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150|unique:graduaciones'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        // Buscar el tipo por su ID en la base de datos.
        $graduacion = Graduacion::find($id);

        if (!$graduacion) {
            return response()->json(['message' => 'graduación no encontrada'], 404);
        }

        // Actualizar los datos del tipo con los datos validados.
        $graduacion->update($request->all());

        // Retornar una respuesta JSON que confirma la actualización exitosa del tipo.
        return response()->json(['message' => 'Graduación actualizado con éxito', 'graduacion' => $graduacion]);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/graduaciones/{id}",
     *      operationId="destroyGraduacion",
     *      tags={"Graduaciones"},
     *      summary="Eliminar una graduación existente",
     *      description="Elimina una graduación existente identificada por su ID y la retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID de la graduación a eliminar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Graduación eliminada con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Graduación eliminado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Graduación no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Graduación no encontrado"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="No se pudo borrar la graduación, tiene cervezas relacionadas",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se pudo borrar la graduación, tiene cervezas relacionadas"),
     *          ),
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */

    public function destroy(string $id)
    {
        // Buscar el tipo por su ID en la base de datos.

        $graduacion = Graduacion::find($id);

        if (!$graduacion) {
            return response()->json(['message' => 'Graduación no encontrada'], 404);
        }

        if ($graduacion->cervezas()->exists()) {
            return response()->json(['message' => 'No se pudo borrar la graduación, tiene cervezas relacionadas'], 400);
        }

        // Eliminar el tipo de la base de datos.
        $graduacion->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del tipo.
        return response()->json(['message' => 'Graduación eliminado con éxito']);
    }
}
