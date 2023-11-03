<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color; // Asegúrate de importar el modelo Color
use Illuminate\Support\Facades\Validator;


class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Método: index
     * Ruta asociada: GET /colors
     * Descripción: Este método muestra una lista de recursos (en este caso, colores).
     */
    public function index()
    {
        // Recuperar todos los colores desde la base de datos y retornarlos como una respuesta JSON
        $colores = Color::all();
        return response()->json(['colores' => $colores]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * Método: create
     * Ruta asociada: GET /colors/create
     * Descripción: Este método muestra el formulario para crear un nuevo recurso (color).
     */
    
    public function store(Request $request)
    {
        // Validación de los datos del nuevo color (por ejemplo, nombre, código de color).
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:colores'
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors(),422); 
        }

        //Debe estar configurado fillable en el modelo para 
        //utilizar inserción masiva
        
        $color=Color::create($request->all());
       
        // Retornar una respuesta JSON que confirma la creación exitosa del color.
        return response()->json(['message' => 'Color creado con éxito', 'color' => $color],201);
    }

    /**
     * Display the specified resource.
     *
     * Método: show
     * Ruta asociada: GET /colors/{id}
     * Descripción: Este método muestra un recurso (color) específico identificado por su ID.
     */
    public function show(string $id)
    {
        // Buscar el color por su ID en la base de datos y retornarlo como una respuesta JSON.
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color no encontrado'], 404);
        }


        return response()->json(['color' => $color]);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * Método: update
     * Ruta asociada: PUT/PATCH /colors/{id}
     * Descripción: Este método actualiza un recurso (color) específico identificado por su ID en el almacenamiento.
     */
    public function update(Request $request, string $id)
    {
        // Validación de los datos actualizados del color.
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255|unique:colores'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422); 
        }
        

        // Buscar el color por su ID en la base de datos.
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color no encontrado'], 404);
        }

        // Actualizar los datos del color con los datos validados.
        $color->update($request->all());

        // Retornar una respuesta JSON que confirma la actualización exitosa del color.
        return response()->json(['message' => 'Color actualizado con éxito', 'color' => $color]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Método: destroy
     * Ruta asociada: DELETE /colors/{id}
     * Descripción: Este método elimina un recurso (color) específico identificado por su ID del almacenamiento.
     */
    public function destroy(string $id)
    {
        // Buscar el color por su ID en la base de datos.
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color no encontrado'], 404);
        }

        // Eliminar el color de la base de datos.
        $color->delete();

        // Retornar una respuesta JSON que confirma la eliminación exitosa del color.
        return response()->json(['message' => 'Color eliminado con éxito']);
    }
}
