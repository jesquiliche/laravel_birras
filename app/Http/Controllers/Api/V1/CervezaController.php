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
use App\Http\Validators\CervezaValidator;

class CervezaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        {
            $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $colorId = $request->input('color_id');
        $paisId = $request->input('pais_id');
        $tipoId = $request->input('tipo_id');
        $novedad = $request->input('novedad');
        $oferta = $request->input('oferta');
        $marca = $request->input('marca');
        $precioDesde = $request->input('precio_desde');
        $precioHasta = $request->input('precio_hasta');

        $query = DB::table('cervezas as cer')
            ->select('cer.id', 'cer.nombre', 'cer.descripcion', 'cer.novedad', 'cer.oferta', 'cer.precio', 'cer.foto', 'cer.marca', 'col.nombre as color', 'g.nombre as graduacion', 't.nombre as tipo', 'p.nombre as pais')
            ->join('colores as col', 'cer.color_id', '=', 'col.id')
            ->join('graduaciones as g', 'cer.graduacion_id', '=', 'g.id')
            ->join('tipos as t', 'cer.tipo_id', '=', 't.id')
            ->join('paises as p', 'cer.pais_id', '=', 'p.id')
            ->orderBy('cer.nombre');

        if ($colorId) {
            $query->where('cer.color_id', $colorId);
        }

        if ($paisId) {
            $query->where('cer.pais_id', $paisId);
        }

        if ($tipoId) {
            $query->where('cer.tipo_id', $tipoId);
        }

        if ($novedad) {
            $query->where('cer.novedad', $novedad);
        }

        if ($oferta) {
            $query->where('cer.oferta', $oferta);
        }

        if ($marca) {
            $query->whereRaw('LOWER(cer.marca) LIKE ?', ['%' . strtolower($marca) . '%']);
        }

        if ($precioDesde && $precioHasta) {
            $query->whereBetween('cer.precio', [$precioDesde, $precioHasta]);
        }

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($results);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|unique:cervezas',
            'descripcion' => 'required',
            'color_id' => 'required|numeric',
            'graduacion_id' => 'required|numeric',
            'tipo_id' => 'required|numeric',
            'pais_id' => 'required|numeric',
            'novedad' => 'required|boolean',
            'oferta' => 'required|boolean',
            'precio' => 'required|numeric',
            'foto' => 'required',
            'marca' => 'required',
        ];

       
        // Realiza la validación
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $color_id=$request->input('color_id');
        $color=Color::find($color_id);
        if (!$color) {
            return response()->json('El color_id '.$color_id." no existe", 404);
        }

        $graduacion_id=$request->input('graduacion_id');
        $graduacion=Graduacion::find($graduacion_id);
        if (!$graduacion) {
            return response()->json('La graduacion_id '.$graduacion_id." no existe", 404);
        }

        $pais_id=$request->input('pais_id');
        $pais=Pais::find($pais_id);
        if (!$pais) {
            return response()->json('El pais_id '.$pais_id." no existe", 404);
        }

        $tipo_id=$request->input('tipo_id');
        $tipo=Tipo::find($tipo_id);
        if (!$tipo) {
            return response()->json('El tipo_id '.$tipo_id." no existe", 404);
        }
        // Si la validación es exitosa, crea la nueva cerveza
        $cerveza = Cerveza::create($request->all());
        return response()->json($cerveza, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Encuentra la cerveza que deseas actualizar
        $cerveza = Cerveza::find($id);

        if (!$cerveza) {
            return response()->json('La cerveza con ID ' . $id . ' no existe', 404);
        }

        // Definir reglas de validación para los campos (similar a store)
        $rules = [
            'nombre' => 'required|unique:cervezas,nombre,' . $id,
            'descripcion' => 'required',
            'color_id' => 'required|numeric',
            'graduacion_id' => 'required|numeric',
            'tipo_id' => 'required|numeric',
            'pais_id' => 'required|numeric',
            'novedad' => 'required|boolean',
            'oferta' => 'required|boolean',
            'precio' => 'required|numeric',
            'foto' => 'required',
            'marca' => 'required',
        ];

        // Realiza la validación
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Actualiza los campos de la cerveza
        $cerveza->update($request->all());

        return response()->json($cerveza, 200); // Devuelve la cerveza actualizada
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
