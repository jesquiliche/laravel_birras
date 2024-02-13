<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Direccion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        
        if ($userId) {
            $direcciones = Direccion::where('user_id', $userId)->get();
        } else {
            $direcciones = Direccion::all();
        }
        
        return response()->json($direcciones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'calle' => 'required|string',
            'numero' => 'required|string',
            'poblacion_id' => 'required|exists:poblaciones,id',
            'provincia_id' => 'required|exists:provincias,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $direccion = Direccion::create($request->all());
        return response()->json($direccion, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $direccion = Direccion::find($id);

        if (!$direccion) {
            return response()->json(['message' => 'Dirección no encontrada'], 404);
        }

        return response()->json($direccion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Direccion $direccion)
    {
        $validator = Validator::make($request->all(), [
            'calle' => 'string',
            'numero' => 'string',
            'poblacion_id' => 'exists:poblaciones,id',
            'provincia_id' => 'exists:provincias,id',
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $direccion->update($request->all());
        return response()->json($direccion, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Direccion $direccion)
    {
        $direccion->delete();
        return response()->json(null, 204);
    }
}
