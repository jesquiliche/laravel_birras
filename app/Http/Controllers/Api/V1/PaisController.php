<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pais;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Pais",
 *     type="object",
 *     title="Paises",
 *     properties={
 *         @OA\Property(property="id", type="integer", format="int64"),
 *         @OA\Property(property="nombre", type="string"),
 *     }
 * )
 */

class PaisController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy', 'update']);
    }

/**
 * @OA\Get(
 *      path="/api/v1/paises",
 *      operationId="indexPais",
 *      tags={"Paises"},
 *      summary="Listar todos los países",
 *      description="Obtiene una lista paginada de todos los países en formato JSON.",
 *      @OA\Parameter(
 *          name="per_page",
 *          in="query",
 *          description="Número de países por página (predeterminado: 20)",
 *          @OA\Schema(type="integer", default=20)
 *      ),
 *      @OA\Parameter(
 *          name="page",
 *          in="query",
 *          description="Número de página a recuperar (predeterminado: 1)",
 *          @OA\Schema(type="integer", default=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Lista paginada de países",
 *          @OA\JsonContent(
 *              @OA\Property(property="paises", type="array", @OA\Items(ref="#/components/schemas/Pais")),
 *          ),
 *      ),
 * )
 */

 public function index(Request $request)
 {
     // Recopila parámetros de consulta desde la solicitud
     $perPage = $request->input('per_page', 20);
     $page = $request->input('page', 1);
 
     // Construye una consulta utilizando el Query Builder de Laravel
     $query = DB::table('paises as p')
         ->select('*')
         ->orderBy('p.nombre');
 
     // Realiza una paginación de los resultados
     $results = $query->paginate($perPage, ['*'], 'page', $page);
 
     // Devuelve una respuesta JSON con los resultados paginados
     return response()->json($results);
 }
 
    /**
     * @OA\Post(
     *      path="/api/v1/paises",
     *      operationId="storePais",
     *      tags={"Paises"},
     *      summary="Crear un nuevo país",
     *      description="Crea un nuevo país con los datos proporcionados en la solicitud y lo retorna como una respuesta JSON.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos del nuevo país",
     *          @OA\JsonContent(
     *              required={"nombre"},
     *              @OA\Property(property="nombre", type="string", maxLength=255, description="Nombre del nuevo país"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="País creado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="País creado con éxito"),
     *              @OA\Property(property="pais", type="object", ref="#/components/schemas/Pais"),
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
        // Validación de los datos del nuevo pais (por ejemplo, nombre, código de pais).
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:paises'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Debe estar configurado fillable en el modelo para 
        //utilizar inserción masiva
        $tipo = Pais::create($request->all());

        // Retornar una respuesta JSON que confirma la creación exitosa del pais.
        return response()->json(['message' => 'País creado con éxito', 'pais' => $tipo]);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/paises/{id}",
     *      operationId="showPais",
     *      tags={"Paises"},
     *      summary="Mostrar un país específico",
     *      description="Muestra un país específico identificado por su ID en una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID del país a mostrar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="País encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="País", type="object", ref="#/components/schemas/Pais"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="País no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="País no encontrado"),
     *          ),
     *      ),
     * )
     */

    public function show(string $id)
    {
        // Buscar el pais por su ID en la base de datos y retornarlo como una respuesta JSON.
        $pais = Pais::find($id);

        if (!$pais) {
            return response()->json(['message' => 'país no encontrado'], 404);
        }

        return response()->json(['País' => $pais]);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/paises/{id}",
     *      operationId="updatePais",
     *      tags={"Paises"},
     *      summary="Actualizar un país existente",
     *      description="Actualiza un país existente identificado por su ID con los datos proporcionados en la solicitud y lo retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID del país a actualizar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos actualizados del país",
     *          @OA\JsonContent(
     *              required={"nombre"},
     *              @OA\Property(property="nombre", type="string", maxLength=255, description="Nombre actualizado del país"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="País actualizado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="País actualizado con éxito"),
     *              @OA\Property(property="pais", type="object", ref="#/components/schemas/Pais"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="País no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="País no encontrado"),
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
            'nombre' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        // Buscar el pais por su ID en la base de datos.
        $pais = Pais::find($id);

        if (!$pais) {
            return response()->json(['message' => 'Pais no encontrado'], 404);
        }

        // Actualizar los datos del pais con los datos validados.
        $pais->update($request->all());

        // Retornar una respuesta JSON que confirma la actualización exitosa del pais.
        return response()->json(['message' => 'País actualizado con éxito', 'pais' => $pais]);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/paises/{id}",
     *      operationId="destroyPais",
     *      tags={"Paises"},
     *      summary="Eliminar un país existente",
     *      description="Elimina un país existente identificado por su ID y lo retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID del país a eliminar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="País eliminado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="País eliminado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="País no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="País no encontrado"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="No se pudo borrar el país, tiene cervezas relacionadas",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se pudo borrar el país, tiene cervezas relacionadas"),
     *          ),
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */

    public function destroy(string $id)
    {
        // Buscar el pais por su ID en la base de datos.
        $pais = Pais::find($id);

        if (!$pais) {
            return response()->json(['message' => 'País no encontrado'], 404);
        }

        if ($pais->cervezas()->exists()) {
            return response()->json(['message' => 'No se pudo borrar el país, tiene cervezas relacionadas'], 400);
        }
        // Eliminar el pais de la base de datos.
        $pais->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del tipo.
        return response()->json(['message' => 'País eliminado con éxito']);
    }
}
