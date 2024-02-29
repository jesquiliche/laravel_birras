<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Cerveza;
use App\Models\Color;
use App\Models\Graduacion;
use App\Models\Pais;
use App\Models\Tipo;
use Exception;
use Illuminate\Support\Facades\Storage;

//@OA\Server(url="https://laravelbirras-production.up.railway.app/")

/**
 * @OA\Info(
 *     title="Cervezas de Importación e-commerce",
 *     version="2.0",
 *     description="Descripcion"
 * )
 *
 * @OA\Server(url="https://laravelbirras-production.up.railway.app/")
 *
 * @OA\Schema(
 *     schema="Cerveza",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="descripcion", type="string"),
 *     @OA\Property(property="color", type="string"),
 *     @OA\Property(property="graduacion", type="string"),
 *     @OA\Property(property="tipo", type="string"),
 *     @OA\Property(property="pais", type="string"),
 *     @OA\Property(property="novedad", type="boolean"),
 *     @OA\Property(property="oferta", type="boolean"),
 *     @OA\Property(property="precio", type="number"),
 *     @OA\Property(property="foto", type="string"),
 *     @OA\Property(property="marca", type="string"),
 * )
 */


class CervezaController extends Controller
{
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Autenticación Bearer JWT",
     *     scheme="bearer",
     *     securityScheme="bearerAuth"
     * )
     */

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy', 'update', 'patch']);
    }
    /**
     * Display a listing of the resource.
     */

    /**
 * @OA\Get(
 *      path="/api/v1/cervezas",
 *      operationId="getCervezas",
 *      tags={"Cervezas"},
 *      summary="Obtener todas las cervezas",
 *      description="Recupera todas las cervezas de la base de datos y las devuelve como una respuesta JSON ordenadas por nombre.",
 *      @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Número de registros por página",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Número de página",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="color_id",
 *         in="query",
 *         description="ID del color de la cerveza",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="pais_id",
 *         in="query",
 *         description="ID del país de origen de la cerveza",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="tipo_id",
 *         in="query",
 *         description="ID del tipo de cerveza",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="graduacion_id",
 *         in="query",
 *         description="ID de la graduación alcohólica de la cerveza",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="novedad",
 *         in="query",
 *         description="Indicador de novedad de la cerveza (true/false)",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Parameter(
 *         name="oferta",
 *         in="query",
 *         description="Indicador de oferta de la cerveza (true/false)",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Parameter(
 *         name="marca",
 *         in="query",
 *         description="Nombre de la marca de la cerveza (búsqueda insensible a mayúsculas/minúsculas)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="nombre",
 *         in="query",
 *         description="Nombre de la cerveza (búsqueda insensible a mayúsculas/minúsculas)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="precio_desde",
 *         in="query",
 *         description="Precio mínimo de la cerveza",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *     @OA\Parameter(
 *         name="precio_hasta",
 *         in="query",
 *         description="Precio máximo de la cerveza",
 *         required=false,
 *         @OA\Schema(type="number")
 *     ),
 *      @OA\Response(
 *          response=200,
 *          description="Lista de cervezas ordenadas por nombre",
 *          @OA\JsonContent(
 *              @OA\Property(property="cervezas", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="nombre", type="string", example="Cerveza A"),
 *                      @OA\Property(property="pais_id", type="integer", example=1),
 *                      @OA\Property(property="descripcion", type="string", example="Descripción de la cerveza"),
 *                      @OA\Property(property="novedad", type="boolean", example=true),
 *                      @OA\Property(property="oferta", type="boolean", example=false),
 *                      @OA\Property(property="precio", type="number", example=5.99),
 *                      @OA\Property(property="foto", type="string", example="url_de_la_imagen"),
 *                      @OA\Property(property="marca", type="string", example="Marca de la cerveza"),
 *                      @OA\Property(property="color", type="string", example="Color de la cerveza"),
 *                      @OA\Property(property="stock", type="integer", example=100),
 *                      @OA\Property(property="graduacion", type="string", example="Alta"),
 *                      @OA\Property(property="tipo", type="string", example="Tipo de cerveza"),
 *                      @OA\Property(property="pais", type="string", example="País de origen"),
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
        // Recopila parámetros de consulta desde la solicitud
        $perPage = $request->input('per_page', 8);
        $page = $request->input('page', 1);
        $colorId = $request->input('color_id');
        $paisId = $request->input('pais_id');
        $tipoId = $request->input('tipo_id');
        $graduacionId = $request->input('graduacion_id');
        $novedad = $request->input('novedad');
        $oferta = $request->input('oferta');
        $marca = $request->input('marca');
        $nombre = $request->input('nombre');
        $precioDesde = $request->input('precio_desde');
        $precioHasta = $request->input('precio_hasta');

        // Construye una consulta utilizando el Query Builder de Laravel
        $query = DB::table('cervezas as cer')
            ->select(
                'cer.id',
                'cer.nombre',
                'cer.pais_id',
                'cer.descripcion',
                'cer.novedad',
                'cer.oferta',
                'cer.precio',
                'cer.foto',
                'cer.marca',
                'col.nombre as color' . 'cer.stock',
                'g.nombre as graduacion',
                't.nombre as tipo',
                'p.nombre as pais'
            )
            ->join('colores as col', 'cer.color_id', '=', 'col.id')
            ->join('graduaciones as g', 'cer.graduacion_id', '=', 'g.id')
            ->join('tipos as t', 'cer.tipo_id', '=', 't.id')
            ->join('paises as p', 'cer.pais_id', '=', 'p.id')
            ->orderBy('cer.id');

        // Aplica condiciones según los parámetros de consulta
        if ($colorId) {
            $query->where('cer.color_id', $colorId);
        }

        if ($paisId) {
            $query->where('cer.pais_id', '=', intval($paisId));
        }

        if ($graduacionId) {
            $query->where('cer.graduacion_id', $graduacionId);
        }

        if ($tipoId) {
            $query->where('cer.tipo_id', $tipoId);
        }

        if ($novedad) {
            $query->where('cer.novedad', $novedad);
        }

        if ($oferta) {

            $query->where('cer.oferta', filter_var($oferta, FILTER_VALIDATE_BOOLEAN));
        }

        if ($marca) {
            // Realiza una búsqueda de marca insensible a mayúsculas y minúsculas
            $query->whereRaw('LOWER(cer.marca) LIKE ?', ['%' . strtolower($marca) . '%']);
        }
        if ($nombre) {
            // Realiza una búsqueda de marca insensible a mayúsculas y minúsculas
            $query->whereRaw('LOWER(cer.nombre) LIKE ?', ['%' . strtolower($nombre) . '%']);
        }

        if ($precioDesde && $precioHasta) {
            $query->whereBetween('cer.precio', [$precioDesde, $precioHasta]);
        }

        // Realiza una paginación de los resultados
        $results = $query->paginate($perPage, ['*'], 'page', $page);
        //print_r($graduacionId);
        // Devuelve una respuesta JSON con los resultados paginados
        return response()->json($results);
    }




    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/v1/cervezas",
     *      operationId="storeCerveza",
     *      tags={"Cervezas"},
     *      summary="Create a new cerveza",
     *      description="Creates a new cerveza and stores it in the database",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="nombre", type="string"),
     *                  @OA\Property(property="descripcion", type="string"),
     *                  @OA\Property(property="color_id", type="integer"),
     *                  @OA\Property(property="graduacion_id", type="integer"),
     *                  @OA\Property(property="tipo_id", type="integer"),
     *                  @OA\Property(property="pais_id", type="integer"),
     *                  @OA\Property(property="novedad", type="boolean"),
     *                  @OA\Property(property="oferta", type="boolean"),
     *                  @OA\Property(property="precio", type="number"),
     *                  @OA\Property(property="foto", type="string", format="binary"),
     *                  @OA\Property(property="marca", type="string"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Cerveza created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="descripcion", type="string"),
     *              @OA\Property(property="color_id", type="integer"),
     *              @OA\Property(property="graduacion_id", type="integer"),
     *              @OA\Property(property="tipo_id", type="integer"),
     *              @OA\Property(property="pais_id", type="integer"),
     *              @OA\Property(property="novedad", type="boolean"),
     *              @OA\Property(property="oferta", type="boolean"),
     *              @OA\Property(property="precio", type="number"),
     *              @OA\Property(property="fILE", type="string"),
     *              @OA\Property(property="foto", type="string"),
     *              @OA\Property(property="marca", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function store(Request $request)
    {
        // Comenzar una transacción de base de datos
        DB::beginTransaction();
        // return $request;

        try {
            // Define las reglas de validación para los campos
            $rules = [
                'nombre' => 'required|string|unique:cervezas|max:150',
                'descripcion' => 'required',
                'color_id' => 'required|numeric',
                'graduacion_id' => 'required|numeric',
                'tipo_id' => 'required|numeric',
                'pais_id' => 'required|numeric',
                'novedad' => 'required|string|in:true,false',
                'oferta' => 'required|string|in:true,false',
                'precio' => 'required|numeric|between:1,1000',
                'foto' => 'required|string',
                'file' => 'required|image|max:2048',
                'marca' => 'required|string|max:150',
                'unidades' => 'required|numeric|between:1,100',
                'stock' => 'required|numeric|between:1,1000',
                'formato' => 'required|string|max:100',
            ];

            $messages = [
                'oferta.in' => 'El campo oferta debe ser "true" o "false".',
                'novedad.in' => 'El campo novedad debe ser "true" o "false".',
                // Mensajes personalizados para otros campos si es necesario...
            ];
            // Realiza la validación de la solicitud
            $validator = Validator::make($request->all(), $rules, $messages);


            // Si la validación falla, devuelve una respuesta JSON con los errores de validación
            if ($validator->fails()) {
                DB::rollback();
                return response()->json($validator->errors(), 400);
            }

            // Valida la existencia de valores relacionados (por ejemplo, color, graduación, país, tipo)

            $color_id = $request->input('color_id');
            $color = Color::find($color_id);
            if (!$color) {
                DB::rollback();
                return response()->json('El color_id ' . $color_id . ' no existe', 404);
            }

            $graduacion_id = $request->input('graduacion_id');
            $graduacion = Graduacion::find($graduacion_id);
            if (!$graduacion) {
                DB::rollback();
                return response()->json('La graduacion_id ' . $graduacion_id . ' no existe', 404);
            }

            $pais_id = $request->input('pais_id');
            $pais = Pais::find($pais_id);
            if (!$pais) {
                DB::rollback();
                return response()->json('El pais_id ' . $pais_id . ' no existe', 404);
            }

            $tipo_id = $request->input('tipo_id');
            $tipo = Tipo::find($tipo_id);
            if (!$tipo) {
                DB::rollback();
                return response()->json('El tipo_id ' . $tipo_id . ' no existe', 404);
            }

            $cerveza = $request->all();
            $cerveza['novedad'] = filter_var($request->input('novedad'), FILTER_VALIDATE_BOOLEAN);
            $cerveza['oferta'] = filter_var($request->input('oferta'), FILTER_VALIDATE_BOOLEAN);

            // Procesa la imagen y guárdala en la carpeta 'storage/images'
            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('/public/images');
                $url = url('/') . '/storage/images/' . basename($path); // 'images' es la subcarpeta donde se almacenará la imagen

                $cerveza['foto'] = $url; // Actualiza el campo 'foto' con la ubicación de la imagen almacenad
            }

            // Guardar la cerveza en la base de datos
            $cerveza = Cerveza::create($cerveza);

            // Confirmar la transacción si todo se completó con éxito
            DB::commit();

            // Devuelve una respuesta JSON con la cerveza recién creada y el código de respuesta 201 (creado)
            return response()->json($cerveza, 201);
        } catch (Exception $e) {
            // Revertir la transacción en caso de fallo
            DB::rollback();

            // Devuelve una respuesta de error
            return response()->json('Error al procesar la solicitud', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *      path="/api/v1/cervezas/{id}",
     *      operationId="getCervezaById",
     *      tags={"Cervezas"},
     *      summary="Get cerveza details by ID",
     *      description="Returns details of a cerveza based on its ID",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the cerveza",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="descripcion", type="string"),
     *              @OA\Property(property="color_id", type="integer"),
     *              @OA\Property(property="graduacion_id", type="integer"),
     *              @OA\Property(property="tipo_id", type="integer"),
     *              @OA\Property(property="pais_id", type="integer"),
     *              @OA\Property(property="novedad", type="boolean"),
     *              @OA\Property(property="oferta", type="boolean"),
     *              @OA\Property(property="precio", type="number"),
     *              @OA\Property(property="foto", type="string"),
     *              @OA\Property(property="marca", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cerveza not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */

    public function show(string $id)
    {
        $query = DB::table('cervezas as cer')
            ->select(
                'cer.id',
                'cer.nombre',
                'cer.descripcion',
                'cer.novedad',
                'cer.oferta',
                'cer.precio',
                'cer.foto',
                'cer.marca',
                'col.nombre as color',
                'g.nombre as graduacion',
                't.nombre as tipo',
                'p.nombre as pais',
                'cer.tipo_id',
                'cer.color_id',
                'cer.pais_id',
                'cer.graduacion_id',
                'cer.formato',
                'cer.unidades',
                'cer.stock'
            )
            ->join('colores as col', 'cer.color_id', '=', 'cer.color_id')
            ->join('graduaciones as g', 'cer.graduacion_id', '=', 'g.id')
            ->join('tipos as t', 'cer.tipo_id', '=', 't.id')
            ->join('paises as p', 'cer.pais_id', '=', 'p.id')
            ->where('cer.id', $id);
        $cerveza = $query->first();
        return response()->json($cerveza, 200);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/cervezas/{id}",
     *      operationId="updateCerveza",
     *      tags={"Cervezas"},
     *      summary="Update cerveza details by ID",
     *      description="Updates details of a cerveza based on its ID",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the cerveza",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Cerveza details to be updated",
     *          @OA\JsonContent(
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="descripcion", type="string"),
     *              @OA\Property(property="color_id", type="integer"),
     *              @OA\Property(property="graduacion_id", type="integer"),
     *              @OA\Property(property="tipo_id", type="integer"),
     *              @OA\Property(property="pais_id", type="integer"),
     *              @OA\Property(property="novedad", type="boolean"),
     *              @OA\Property(property="oferta", type="boolean"),
     *              @OA\Property(property="precio", type="number"),
     *              @OA\Property(property="foto", type="string"),
     *              @OA\Property(property="marca", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="descripcion", type="string"),
     *              @OA\Property(property="color_id", type="integer"),
     *              @OA\Property(property="graduacion_id", type="integer"),
     *              @OA\Property(property="tipo_id", type="integer"),
     *              @OA\Property(property="pais_id", type="integer"),
     *              @OA\Property(property="novedad", type="boolean"),
     *              @OA\Property(property="oferta", type="boolean"),
     *              @OA\Property(property="precio", type="number"),
     *              @OA\Property(property="foto", type="string"),
     *              @OA\Property(property="marca", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cerveza not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function update(Request $request, $id)
    {
        // El código del método permanece sin cambios
    }

    /**
     * @OA\Patch(
     *      path="/api/v1/cervezas/{id}",
     *      operationId="patchCerveza",
     *      tags={"Cervezas"},
     *      summary="Patch cerveza details by ID",
     *      description="Partially updates details of a cerveza based on its ID",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the cerveza",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Cerveza details to be partially updated",
     *          @OA\JsonContent(
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="descripcion", type="string"),
     *              @OA\Property(property="color_id", type="integer"),
     *              @OA\Property(property="graduacion_id", type="integer"),
     *              @OA\Property(property="tipo_id", type="integer"),
     *              @OA\Property(property="pais_id", type="integer"),
     *              @OA\Property(property="novedad", type="boolean"),
     *              @OA\Property(property="oferta", type="boolean"),
     *              @OA\Property(property="precio", type="number"),
     *              @OA\Property(property="foto", type="string"),
     *              @OA\Property(property="marca", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="descripcion", type="string"),
     *              @OA\Property(property="color_id", type="integer"),
     *              @OA\Property(property="graduacion_id", type="integer"),
     *              @OA\Property(property="tipo_id", type="integer"),
     *              @OA\Property(property="pais_id", type="integer"),
     *              @OA\Property(property="novedad", type="boolean"),
     *              @OA\Property(property="oferta", type="boolean"),
     *              @OA\Property(property="precio", type="number"),
     *              @OA\Property(property="foto", type="string"),
     *              @OA\Property(property="marca", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cerveza not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function patch(Request $request, $id)
    {
        // Comenzar una transacción de base de datos
        DB::beginTransaction();

        try {
            // Encuentra la cerveza que deseas actualizar
            $cerveza = Cerveza::find($id);


            if (!$cerveza) {
                DB::rollback();
                return response()->json('La cerveza con ID ' . $id . ' no existe', 404);
            }

            // Valida la existencia de valores relacionados (por ejemplo, color, graduación, país, tipo)
            // ...

            // Actualiza los campos de la cerveza solo si están presentes en la solicitud
            // Actualiza los campos de la cerveza solo si están presentes en la solicitud

            $cerveza->nombre = $request->json('nombre', $cerveza->nombre);
            $cerveza->descripcion = $request->json('descripcion', $cerveza->descripcion);
            $cerveza->color_id = $request->json('color_id', $cerveza->color_id);
            //$cerveza->graduacion_id = $request->json('graduacion_id', $cerveza->graduacion_id);
            $cerveza->tipo_id = $request->json('tipo_id', $cerveza->tipo_id);
            $cerveza->pais_id = $request->json('pais_id', $cerveza->pais_id);
            $cerveza->novedad = $request->json('novedad', $cerveza->novedad);
            $cerveza->oferta = $request->json('oferta', $cerveza->oferta);
            $cerveza->precio = $request->json('precio', $cerveza->precio);
            $cerveza->marca = $request->json('marca', $cerveza->marca);
            $cerveza->formato = $request->json('formato', $cerveza->formato);
            $cerveza->unidades = $request->json('unidades', $cerveza->unidades);
            $cerveza->stock = $request->json('stock', $cerveza->stock);


            // Guarda la cerveza
            $cerveza->save();




            // Confirmar la transacción si todo se completó con éxito
            DB::commit();

            return response()->json($cerveza, 200); // Devuelve la cerveza actualizada
        } catch (Exception $e) {
            // Revertir la transacción en caso de fallo
            DB::rollback();

            // Devuelve una respuesta de error
            return response()->json('Error al procesar la solicitud', 500);
        }
    }


    /**
     * @OA\Delete(
     *      path="/api/v1/cervezas/{id}",
     *      operationId="deleteCerveza",
     *      tags={"Cervezas"},
     *      summary="Delete a cerveza by ID",
     *      description="Deletes a cerveza based on its ID",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the cerveza",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cerveza not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */

    public function destroy(string $id)
    {
        // Comienza una transacción de base de datos
        DB::beginTransaction();

        try {
            // Encuentra el modelo que deseas eliminar
            $cerveza = Cerveza::find($id);

            if (!$cerveza) {
                DB::rollback();
                return response()->json('El recurso con ID ' . $id . ' no existe', 404);
            }

            // Elimina la imagen asociada si existe
            if (!empty($cerveza->foto)) {
                Storage::delete('public/images/' . basename($cerveza->foto));
            }

            // Elimina el modelo
            $cerveza->delete();

            // Confirmar la transacción si todo se completó con éxito
            DB::commit();

            return response()->json('Recurso eliminado correctamente', 200);
        } catch (Exception $e) {
            // Revertir la transacción en caso de fallo
            DB::rollback();

            // Devuelve una respuesta de error
            return response()->json('Error al procesar la solicitud', 500);
        }
    }
}
