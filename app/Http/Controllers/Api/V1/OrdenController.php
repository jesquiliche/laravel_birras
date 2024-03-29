<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\Cerveza;
use App\Models\Detalle;
use App\Models\OrdenDireccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdenController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy', 'update']);
    }

    public function index(Request $request)
{
    $user_id = $request->input('user_id');

    if ($user_id) {
        $ordenes = Orden::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
    } else {
        $ordenes = Orden::orderBy('id', 'DESC')->get();
    }

    return $ordenes;
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/ordenes",
     *     summary="Crear una nueva orden",
     *     tags={"Ordenes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"address", "articulos"},
     *             @OA\Property(property="address", type="object", description="Datos de dirección",
     *                 @OA\Property(property="nombre", type="string", example="Jesús"),
     *                 @OA\Property(property="apellidos", type="string", example="Quintana Esquiliche"),
     *                 @OA\Property(property="calle", type="string", example="C/ América"),
     *                 @OA\Property(property="numero", type="string", example="34"),
     *                 @OA\Property(property="escalera", type="string", example=null),
     *                 @OA\Property(property="piso", type="string", example="1"),
     *                 @OA\Property(property="puerta", type="string", example="4"),
     *                 @OA\Property(property="poblacion", type="string", example="08007"),
     *                 @OA\Property(property="provincia", type="string", example="08"),
     *                 @OA\Property(property="user_id", type="integer", example="1"),
     *                 @OA\Property(property="telefono", type="string", example="632816055")
     *             ),
     *             @OA\Property(property="articulos", type="array", description="Lista de artículos",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example="15"),
     *                     @OA\Property(property="cantidad", type="integer", example="10")
     *                 ),
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example="23"),
     *                     @OA\Property(property="cantidad", type="integer", example="4")
     *                 ),
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example="19"),
     *                     @OA\Property(property="cantidad", type="integer", example="1")
     *                 )
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Orden creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Orden creada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="La cantidad de stock es insuficiente para uno o más artículos",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La cantidad de stock es insuficiente para uno o más artículos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al procesar la orden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al procesar la orden: mensaje de error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // Obtener los datos de dirección y artículos del cuerpo de la solicitud
            $address = $request->input('address');
            $articulos = $request->input('articulos');
            $subtotal = 0;
            $total = 0;
            $iva = 0;
            $cantidad_articulos = 0;

            foreach ($articulos as $articulo) {
                // Acceder al id y cantidad del artículo
                $articuloId = $articulo['id'];
                $cantidad = $articulo['cantidad'];

                // Buscar la cerveza por su id
                $cerveza = Cerveza::find($articuloId);

                // Calcular el total acumulando el precio de cada artículo
                $total += $cantidad * $cerveza->precio;
                $cantidad_articulos += $cantidad;
            }

            // Calcular el subtotal y el IVA
            $subtotal = round(($total / 1.21), 2);
            $total = round($total, 2);
            $iva = round($total - $subtotal, 2);

            // Crear una nueva instancia de Orden y establecer los valores
            $orden = new Orden();
            $orden->user_id = $address['user_id'];
            $orden->subtotal = $subtotal;
            $orden->iva = $iva;
            $orden->total = $total;
            $orden->articulos = $cantidad_articulos;

            // Guardar la orden en la base de datos
            $orden->save();
            $ordenId = $orden->id;

            foreach ($articulos as $articulo) {
                $articuloD = new Detalle();

                $articuloD['orden_id'] = $ordenId;
                // Buscar la cerveza por su id
                $articuloId = $articulo['id'];
                $cerveza = Cerveza::find($articuloId);

                if ($articulo['cantidad'] > $cerveza->stock) {
                    DB::rollBack();
                    return response()->json(['error' => 'La cantidad de stock es insuficiente para el artículo: ' . $cerveza->nombre . '. Stock disponible: ' . $cerveza->stock], 422);
                } else {
                    $cerveza->stock = $cerveza->stock - $articulo['cantidad'];
                    $cerveza->update();
                }
                $articuloD->cantidad = $articulo['cantidad'];
                $articuloD->precio = $cerveza->precio;
                $articuloD->cerveza_id = $articuloId;
                $articuloD->save();
            }

            // Obtener los datos de dirección del cuerpo de la solicitud
            $addressData = $request->input('address');

            // Asignar el ID de la orden al addressData
            $addressData['orden_id'] = $orden->id;

            // Guardar la dirección en la base de datos utilizando el método create
            OrdenDireccion::create($addressData);
            DB::commit();
            return response()->json([
                'message' => 'Orden creada exitosamente',
                'orden' => $orden
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => 'Error al procesar la orden: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/pagarorden/{id}",
     *     summary="Marcar una orden como pagada",
     *     tags={"Ordenes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la orden a pagar",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transactionId"},
     *             @OA\Property(property="transactionId", type="string", description="ID de transacción del pago")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La orden ha sido marcada como pagada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Orden pagada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orden no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Orden no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Los datos proporcionados son inválidos")
     *         )
     *     )
     * )
     */
    public function pagarOrden(string $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transactionId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Los datos proporcionados son inválidos'], 422);
        }

        $orden = Orden::find($id);

        if (!$orden) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        $orden->transactionId = $request->input('transactionId');
        $orden->pagado = 'S';
        $orden->save();

        return response()->json(['message' => 'Orden pagada correctamente'], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/ordenes/{id}",
     *      operationId="showOrden",
     *      tags={"Ordenes"},
     *      summary="Mostrar una Ordem específico",
     *      description="Muestra una orden específico identificado por su ID en una respuesta JSON.",
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
        $orden = Orden::find($id);
        $detalle = Detalle::join('cervezas', 'detalles.cerveza_id', '=', 'cervezas.id')
            ->select(
                'detalles.*',
                'cervezas.nombre',
                'cervezas.foto as foto'
            )->where('detalles.orden_id', $id)
            ->get();
        $direccion = OrdenDireccion::join('poblaciones as p', 'orden_direcciones.poblacion', '=', 'p.codigo')
            ->join('provincias as pr', 'orden_direcciones.provincia', '=', 'pr.codigo')
            ->select('orden_direcciones.*', 'p.nombre as poblacion_nombre', 'pr.nombre as provincia_nombre')
            ->where('orden_id', $id)->first();

        return response()->json([
            'orden' => $orden,
            'detalle' => $detalle,
            'direccion' => $direccion,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orden $orden)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orden $orden)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orden $orden)
    {
        //
    }
}
