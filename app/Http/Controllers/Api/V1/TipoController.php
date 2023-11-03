<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tipo;
use Illuminate\Support\Facades\Validator;


class TipoController extends Controller
{
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
        $tipos = Tipo::all();
        return response()->json(['tipos' => $tipos]);
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
        $tipo=Tipo::create($request->all());
       
        // Retornar una respuesta JSON que confirma la creación exitosa del tipo.
        return response()->json(['message' => 'Tipo creado con éxito', 'tipo' => $tipo]);
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
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }


        return response()->json(['Tipo' => $tipo]);
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
            'nombre' => 'required|string|max:150|unique:tipos'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422); 
        }
        

        // Buscar el tipo por su ID en la base de datos.
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'tipo no encontrado'], 404);
        }

        // Actualizar los datos del tipo con los datos validados.
        $tipo->update($request->all());

        // Retornar una respuesta JSON que confirma la actualización exitosa del tipo.
        return response()->json(['message' => 'Tipo actualizado con éxito', 'tipo' => $tipo]);
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