<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Cerveza;
use App\Models\Detalle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

            foreach ($articulos as $articulo) {
                // Acceder al id y cantidad del artículo
                $articuloId = $articulo['id'];
                $cantidad = $articulo['cantidad'];

                // Buscar la cerveza por su id
                $cerveza = Cerveza::find($articuloId);

                // Calcular el total acumulando el precio de cada artículo
                $total += $cantidad * $cerveza->precio;
            }

            // Calcular el subtotal y el IVA
            $total=round($total,2);
            $subtotal = round($total / 1.21,2);
            $iva = round($total - $subtotal,2);

            // Crear una nueva instancia de Orden y establecer los valores
            $orden = new Orden();
            $orden->user_id = $address['user_id'];
            $orden->subtotal = $subtotal;
            $orden->iva = $iva;
            $orden->total = $total;

            // Guardar la orden en la base de datos
            $orden->save();
            $ordenId=$orden->id;

            foreach ($articulos as $articulo) {
                $articuloD=new Detalle();
                
                $articuloD['orden_id']=$ordenId;
                // Buscar la cerveza por su id
                $articuloId=$articulo['id'];
                $cerveza = Cerveza::find($articuloId);
                print($articulo['cantidad']." ".$cerveza->stock."\n");
                if($articulo['cantidad']>$cerveza->stock){
                    throw new Exception('La cantidad de stock es insuficiente para el artículo: ' . $cerveza->nombre);
                } else {
                    $cerveza->stock=$cerveza->stock-$articulo['cantidad'];
                    $cerveza->update();
                }
                $articuloD->cantidad=$articulo['cantidad'];
                $articuloD->precio=$cerveza->precio;
                $articuloD->cerveza_id=$articuloId;
                $articuloD->save();
            }

            DB::commit();
            return response()->json(['message' => 'Orden creada exitosamente'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => 'Error al procesar la orden: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Orden $orden)
    {
        //
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
