<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pais;
use Illuminate\Support\Facades\Validator;

class PaisController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * Método: index
     * Ruta asociada: GET /paises
     * Descripción: Este método muestra una lista de recursos (en este caso, paises).
     */
    public function index()
    {
        // Recuperar todos los paises desde la base de datos y retornarlos como una respuesta JSON
        $paises = Pais::all();
        return response()->json(['paises' => $paises]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * Método: create
     * Ruta asociada: POST /paises
     * Descripción: Este método muestra el formulario para crear un nuevo recurso (pais).
     */
    
    public function store(Request $request)
    {
        // Validación de los datos del nuevo pais (por ejemplo, nombre, código de pais).
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:paises'
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors(),422); 
        }

        //Debe estar configurado fillable en el modelo para 
        //utilizar inserción masiva
        $tipo=Pais::create($request->all());
       
        // Retornar una respuesta JSON que confirma la creación exitosa del pais.
        return response()->json(['message' => 'País creado con éxito', 'pais' => $tipo]);
    }

    /**
     * Display the specified resource.
     *
     * Método: show
     * Ruta asociada: GET /paiss/{id}
     * Descripción: Este método muestra un recurso (pais) específico identificado por su ID.
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
     * Update the specified resource in storage.
     *
     * Método: update
     * Ruta asociada: PUT/PATCH /paises/{id}
     * Descripción: Este método actualiza un recurso (pais) específico identificado por su ID en el almacenamiento.
     */
    public function update(Request $request, string $id)
    {
        // Validación de los datos actualizados del tipo.
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422); 
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
     * Remove the specified resource from storage.
     *
     * Método: destroy
     * Ruta asociada: DELETE /paises/{id}
     * Descripción: Este método elimina un recurso (pais) específico identificado por su ID del almacenamiento.
     */
    public function destroy(string $id)
    {
        // Buscar el pais por su ID en la base de datos.
        $pais = Pais::find($id);

        if (!$pais) {
            return response()->json(['message' => 'País no encontrado'], 404);
        }

        if ($pais->cervezas()->exists()) {
            return response()->json(['message' => 'No se pudo borrar el país, tiene cervezas relacionadas'],400);
        }
        // Eliminar el pais de la base de datos.
        $pais->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del tipo.
        return response()->json(['message' => 'País eliminado con éxito']);
    }//
}
