<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Graduacion;
use Illuminate\Support\Facades\Validator;


class GraduacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'destroy','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * Método: index
     * Ruta asociada: GET /tipos
     * Descripción: Este método muestra una lista de recursos (en este caso, tipoes).
     */
    public function index()
    {
        // Recuperar todos los tipoes desde la base de datos y retornarlos como una respuesta JSON
        $graduaciones = Graduacion::all();
        return response()->json(['graduaciones' => $graduaciones]);
    }

    
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
        $graduacion=Graduacion::create($request->all());
       
        // Retornar una respuesta JSON que confirma la creación exitosa del tipo.
        return response()->json(['message' => 'Graduación creado con éxito', 'graduacion' => $graduacion]);
    }

    /**
     * Display the specified resource.
     *
     * Método: show
     * Ruta asociada: GET /tipos/{id}
     * Descripción: Este método muestra un recurso (tipo) específico identificado por su ID.
     */
    public function show(string $id)
    {
        // Buscar el tipo por su ID en la base de datos y retornarlo como una respuesta JSON.
        $graduacion = Graduacion::find($id);

        if (!$graduacion) {
            return response()->json(['message' => 'Graduación no encontrado'], 404);
        }


        return response()->json(['Tipo' => $graduacion]);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * Método: update
     * Ruta asociada: PUT/PATCH /itposs/{id}
     * Descripción: Este método actualiza un recurso (tipo) específico identificado por su ID en el almacenamiento.
     */
    public function update(Request $request, string $id)
    {
        // Validación de los datos actualizados del tipo.
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:150|unique:graduaciones'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422); 
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
     * Remove the specified resource from storage.
     *
     * Método: destroy
     * Ruta asociada: DELETE /tipos/{id}
     * Descripción: Este método elimina un recurso (tipo) específico identificado por su ID del almacenamiento.
     */
    public function destroy(string $id)
    {
        // Buscar el tipo por su ID en la base de datos.

        $graduacion = Graduacion::find($id);

        if (!$graduacion) {
            return response()->json(['message' => 'Graduación no encontrada'], 404);
        }

        if ($graduacion->cervezas()->exists()) {
            return response()->json(['message' => 'No se pudo borrar la graduación, tiene cervezas relacionadas'],400);
        }


        // Eliminar el tipo de la base de datos.
        $graduacion->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del tipo.
        return response()->json(['message' => 'Graduación eliminado con éxito']);
    }
}