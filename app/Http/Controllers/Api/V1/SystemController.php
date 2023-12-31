<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/consultaCervezasPorPais",
     *      operationId="consultaCervezasPorPais",
     *      tags={"System"},
     *      summary="Consulta la cantidad de cervezas por país",
     *      description="Devuelve la cantidad de cervezas agrupadas por país",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="cantidad", type="integer"),
     *                  @OA\Property(property="nombre", type="string"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function consultaCervezasPorPais()
    {
        $resultados = DB::select("
            SELECT COUNT(*) as value, p.nombre as name
            FROM cervezas as cer
            INNER JOIN paises AS p ON cer.pais_id = p.id
            GROUP BY cer.pais_id, p.nombre
            ORDER BY p.nombre
        ");

        return response()->json($resultados);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/consultaCervezasPorTipo",
     *      operationId="consultaCervezasPorTipo",
     *      tags={"System"},
     *      summary="Consulta la cantidad de cervezas por tipo",
     *      description="Devuelve la cantidad de cervezas agrupadas por tipo",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="cantidad", type="integer"),
     *                  @OA\Property(property="nombre", type="string"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function consultaCervezasPorTipo()
    {
        $resultados = DB::select("
            SELECT COUNT(*) as value, t.nombre as name
            FROM cervezas as cer
            INNER JOIN tipos AS t ON cer.tipo_id = t.id
            GROUP BY cer.tipo_id, t.nombre
            ORDER BY t.nombre
        ");

        return response()->json($resultados);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/consultaBD",
     *      operationId="consultaBD",
     *      tags={"System"},
     *      summary="Consulta el tamaño de las tablas de la base de datos",
     *      description="Devuelve el tamaño de las tablas de la base de datos en megabytes",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="table_name", type="string"),
     *                  @OA\Property(property="table_rows", type="integer"),
     *                  @OA\Property(property="data_size_mb", type="number"),
     *                  @OA\Property(property="index_size_mb", type="number"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function consultaBD()
    {
        $databaseName = env('DB_DATABASE');
        $resultados = DB::select("
            SELECT 
            table_name,
            table_rows,
            data_length / (1024 * 1024) AS data_size_mb,
            index_length / (1024 * 1024) AS index_size_mb
            FROM information_schema.tables
            WHERE table_schema = '{$databaseName}'
            AND table_type = 'BASE TABLE'; -- Solo tablas, no vistas ni tablas de sistema;
        ");

        return response()->json($resultados);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/consultaTablas",
     *      operationId="consultaTablas",
     *      tags={"System"},
     *      summary="Consulta las tablas de la base de datos",
     *      description="Devuelve las tablas de la base de datos",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="table_name", type="string"),
     *                  @OA\Property(property="table_rows", type="integer"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     * )
     */
    public function consultaTablas()
    {
        $databaseName = env('DB_DATABASE');
    
        $resultados = DB::select("
            SELECT table_name, table_rows
            FROM information_schema.tables
            WHERE table_schema = '{$databaseName}'
              AND table_type = 'BASE TABLE'; -- Solo tablas, no vistas ni tablas de sistema
        ");
    
        return response()->json($resultados);
    }
    
};