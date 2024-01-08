<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tipo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Tipo",
 *     type="object",
 *     title="Tipos",
 *     properties={
 *         @OA\Property(property="id", type="integer", format="int64"),
 *         @OA\Property(property="nombre", type="string"),
 *     }
 * )
 */



class TipoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy']);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/tipos",
     *      operationId="indexTipo",
     *      tags={"Tipos"},
     *      summary="Listar todos los tipos",
     *      description="Muestra una lista de todos los tipos en una respuesta JSON.",
     *      @OA\Response(
     *          response=200,
     *          description="Lista de tipos",
     *          @OA\JsonContent(
     *              @OA\Property(property="tipos", type="array", @OA\Items(ref="#/components/schemas/Tipo")),
     *          ),
     *      ),
     * )
     */
   
    public function index(Request $request)
    {
       
         // Recopila parámetros de consulta desde la solicitud
         $perPage = $request->input('per_page', 8);
         $page = $request->input('page', 1);
       
         // Construye una consulta utilizando el Query Builder de Laravel
         $query = DB::table('tipos as tip')
             ->select('*')
             ->orderBy('tip.nombre');
 
        $results=$query->get();
         // Realiza una paginación de los resultados
        // $results = $query->paginate($perPage, ['*'], 'page', $page);
        // Devuelve una respuesta JSON con los resultados paginados
         return response()->json($results);
 
    }

       /**
     * @OA\Post(
     *      path="/api/v1/tipos",
     *      operationId="storeTipo",
     *      tags={"Tipos"},
     *      summary="Crear un nuevo tipo",
     *      description="Crea un nuevo tipo con los datos proporcionados en la solicitud y lo retorna como una respuesta JSON.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos del nuevo tipo",
     *          @OA\JsonContent(
     *              required={"nombre"},
     *              @OA\Property(property="nombre", type="string", maxLength=150, description="Nombre del nuevo tipo"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Tipo creado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tipo creado con éxito"),
     *              @OA\Property(property="tipo", type="object", ref="#/components/schemas/Tipo"),
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
            'nombre' => 'required|string|max:150|unique:tipos'
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors(),422); 
        }

        //Debe estar configurado fillable en el modelo para 
        //utilizar inserción masiva
        $tipo=Tipo::create($request->all());
       
        // Retornar una respuesta JSON que confirma la creación exitosa del tipo.
        return response()->json(['message' => 'Tipo creado con éxito', 'tipo' => $tipo]);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/tipos/{id}",
     *      operationId="showTipo",
     *      tags={"Tipos"},
     *      summary="Mostrar un tipo específico",
     *      description="Muestra un tipo específico identificado por su ID en una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID del tipo a mostrar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Tipo encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="Tipo", type="object", ref="#/components/schemas/Tipo"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Tipo no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tipo no encontrado"),
     *          ),
     *      ),
     * )
     */
    public function show(string $id)
    {
        // Buscar el tipo por su ID en la base de datos y retornarlo como una respuesta JSON.
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }


        return response()->json(['Tipo' => $tipo]);
    }

    
   /**
     * @OA\Put(
     *      path="/api/v1/tipos/{id}",
     *      operationId="updateTipo",
     *      tags={"Tipos"},
     *      summary="Actualizar un tipo existente",
     *      description="Actualiza un tipo existente identificado por su ID con los datos proporcionados en la solicitud y lo retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID del tipo a actualizar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos actualizados del tipo",
     *          @OA\JsonContent(
     *              required={"nombre"},
     *              @OA\Property(property="nombre", type="string", maxLength=150, description="Nombre actualizado del tipo"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Tipo actualizado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tipo actualizado con éxito"),
     *              @OA\Property(property="tipo", type="object", ref="#/components/schemas/Tipo"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Tipo no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tipo no encontrado"),
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
        'nombre' => 'required|string|max:100',
        'descripcion' => 'required|string',
    ]);

    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Buscar el tipo por su ID en la base de datos.
    $tipo = Tipo::find($id);

    if (!$tipo) {
        return response()->json(['message' => 'tipo no encontrado'], 404);
    }
    $tipo->nombre = $request->input('nombre');
    $tipo->descripcion = $request->input('descripcion');
    $tipo->save();
    // Actualizar los datos del tipo con los datos validados.
  //  $tipo->update($request->all());

    // Retornar una respuesta JSON que confirma la actualización exitosa del tipo.
    return response()->json(['message' => 'Tipo actualizado con éxito', 'tipo' => $tipo]);
}

    /**
     * @OA\Delete(
     *      path="/api/v1/tipos/{id}",
     *      operationId="destroyTipo",
     *      tags={"Tipos"},
     *      summary="Eliminar un tipo existente",
     *      description="Elimina un tipo existente identificado por su ID y lo retorna como una respuesta JSON.",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="ID del tipo a eliminar",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Tipo eliminado con éxito",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tipo eliminado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Tipo no encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tipo no encontrado"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="No se pudo borrar el tipo, tiene cervezas relacionadas",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se pudo borrar el tipo, tiene cervezas relacionadas"),
     *          ),
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */

    public function destroy(string $id)
    {
        // Buscar el tipo por su ID en la base de datos.
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        if ($tipo->cervezas()->exists()) {
            return response()->json(['message' => 'No se pudo borrar el tipo, tiene cervezas relacionadas'],400);
        }
        // Eliminar el tipo de la base de datos.
        $tipo->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del tipo.
        return response()->json(['message' => 'Tipo eliminado con éxito']);
    }
}