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
use Exception;
use Illuminate\Support\Facades\Storage;

class CervezaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Recopila parámetros de consulta desde la solicitud
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

        // Construye una consulta utilizando el Query Builder de Laravel
        $query = DB::table('cervezas as cer')
            ->select('cer.id', 'cer.nombre', 'cer.descripcion', 'cer.novedad', 'cer.oferta', 'cer.precio', 'cer.foto', 'cer.marca', 'col.nombre as color', 'g.nombre as graduacion', 't.nombre as tipo', 'p.nombre as pais')
            ->join('colores as col', 'cer.color_id', '=', 'col.id')
            ->join('graduaciones as g', 'cer.graduacion_id', '=', 'g.id')
            ->join('tipos as t', 'cer.tipo_id', '=', 't.id')
            ->join('paises as p', 'cer.pais_id', '=', 'p.id')
            ->orderBy('cer.nombre');

        // Aplica condiciones según los parámetros de consulta
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
            // Realiza una búsqueda de marca insensible a mayúsculas y minúsculas
            $query->whereRaw('LOWER(cer.marca) LIKE ?', ['%' . strtolower($marca) . '%']);
        }

        if ($precioDesde && $precioHasta) {
            $query->whereBetween('cer.precio', [$precioDesde, $precioHasta]);
        }

        // Realiza una paginación de los resultados
        $results = $query->paginate($perPage, ['*'], 'page', $page);

        // Devuelve una respuesta JSON con los resultados paginados
        return response()->json($results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Comenzar una transacción de base de datos
        DB::beginTransaction();

        try {
            // Define las reglas de validación para los campos
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
                'foto' => 'required|image|max:2048',
                'marca' => 'required',
            ];

            // Realiza la validación de la solicitud
            $validator = Validator::make($request->all(), $rules);

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
            // Procesa la imagen y guárdala en la carpeta 'storage/images'
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('/public/images');
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
    public function show(string $id)
    {
        // La lógica para mostrar una cerveza individual se puede agregar aquí si es necesario.
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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

            // Define las reglas de validación para los campos
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
                'foto' => 'sometimes|image|max:2048',
                'marca' => 'required',
            ];

            // Realiza la validación de la solicitud
            $validator = Validator::make($request->all(), $rules);

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

            // Actualiza los campos de la cerveza
            $cerveza->update($request->all());

            // Actualiza la imagen si se proporciona una nueva
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('/public/images');
                $url = url('/') . '/storage/images/' . basename($path);
                $cerveza->foto = $url;
                $cerveza->save();
            }

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
            $cerveza->graduacion_id = $request->json('graduacion_id', $cerveza->graduacion_id);
            $cerveza->tipo_id = $request->json('tipo_id', $cerveza->tipo_id);
            $cerveza->pais_id = $request->json('pais_id', $cerveza->pais_id);
            $cerveza->novedad = $request->json('novedad', $cerveza->novedad);
            $cerveza->oferta = $request->json('oferta', $cerveza->oferta);
            $cerveza->precio = $request->json('precio', $cerveza->precio);
            $cerveza->marca = $request->json('marca', $cerveza->marca);

            // Guarda la cerveza
            $cerveza->save();


            // Guarda la cerveza
            $cerveza->save();

            // Actualiza la imagen si se proporciona una nueva
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('/public/images');
                $url = url('/') . '/storage/images/' . basename($path);
                $cerveza->foto = $url;
                $cerveza->save();
            }

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
     * Remove the specified resource from storage.
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
