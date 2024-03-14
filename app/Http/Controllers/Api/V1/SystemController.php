<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cerveza;
use App\Models\Color;
use App\Models\Graduacion;
use App\Models\Pais;
use App\Models\Tipo;
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
     *                  @OA\Property(property="value", type="integer"),
     *                  @OA\Property(property="name", type="string"),
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
     *      path="/api/v1/ventaCervezasPorArticulo",
     *      operationId="ventaCervezasPorArticulo",
     *      tags={"System"},
     *      summary="Consulta las ventas de cervezas por artículo",
     *      description="Devuelve la cantidad de cervezas vendidas y el importe total agrupado por artículo",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="cantidad", type="integer"),
     *                  @OA\Property(property="importe", type="number"),
     *                  @OA\Property(property="stock", type="integer"),
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
    public function ventaCervezasPorArticulo()
    {
        $resultados = DB::select("
    SELECT sum(cantidad) as cantidad,
    sum(d.precio*cantidad) importe ,
    stock,nombre 
    FROM detalles d inner join cervezas c 
    on cerveza_id=c.id group by cerveza_id order by cantidad desc");
        return response()->json($resultados);
    }



/**
 * @OA\Get(
 *     path="/api/v1/cervezasMasVendidas",
 *     summary="Obtener las cervezas más vendidas",
 *     description="Obtiene las cervezas más vendidas en función de la cantidad total vendida de cada una.",
 *     tags={"System"},
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa. Devuelve un JSON con las cervezas más vendidas.",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Cerveza")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor."
 *     )
 * )
 */
public function cervezasMasVendidas()
{
    $resultados = DB::select("
        SELECT c.*, sum(cantidad) as cantidad
        FROM detalles d 
        INNER JOIN cervezas c ON cerveza_id = c.id 
        GROUP BY cerveza_id 
        ORDER BY cantidad DESC LIMIT 12
    ");

    return response()->json($resultados);
}

/**
 * @OA\Get(
 *      path="/api/v1/ventaCervezasPorPais",
 *      operationId="ventaCervezasPorPais",
 *      tags={"System"},
 *      summary="Consulta las ventas de cervezas por país",
 *      description="Devuelve la cantidad de cervezas vendidas y el importe total agrupado por país",
 *      @OA\Response(
 *          response=200,
 *          description="Operación exitosa",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="cantidad", type="integer"),
 *                  @OA\Property(property="importe", type="number"),
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
public function ventaCervezasPorPais()
{
    $resultados = DB::select("
        SELECT SUM(cantidad) AS cantidad, SUM(d.precio * cantidad) AS importe, p.nombre
        FROM detalles d 
        INNER JOIN cervezas c ON cerveza_id = c.id 
        INNER JOIN paises p ON c.pais_id = p.id
        GROUP BY c.pais_id
        ORDER BY cantidad DESC
    ");
    return response()->json($resultados);
}

    /**
     * @OA\Get(
     *      path="/api/v1/stockPorPais",
     *      operationId="stockPorPais",
     *      tags={"System"},
     *      summary="Consulta el stock de cervezas por país",
     *      description="Devuelve el stock total de cervezas agrupado por país",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="value", type="integer"),
     *                  @OA\Property(property="name", type="string"),
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
    public function stockPorPais()
    {
        try {
            $resultados = DB::select("
        SELECT CAST(SUM(stock) AS SIGNED) as value, p.nombre as name
        FROM cervezas as cer 
        INNER JOIN paises as p ON cer.pais_id=p.id
        GROUP BY p.id
        ORDER BY value DESC
        ");

            return response()->json($resultados);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
     *                  @OA\Property(property="value", type="integer"),
     *                  @OA\Property(property="name", type="string"),
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
     *      path="/api/v1/consultaCervezasPorColores",
     *      operationId="consultaCervezasPorColores",
     *      tags={"System"},
     *      summary="Consulta la cantidad de cervezas por Colores",
     *      description="Devuelve la cantidad de cervezas agrupadas por colores",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="value", type="integer"),
     *                  @OA\Property(property="name", type="string"),
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

    public function consultaCervezasColores()
    {
        $resultados = DB::select("
            SELECT COUNT(*) as value, c.nombre as name
            FROM cervezas as cer
            INNER JOIN colores AS c ON cer.tipo_id = c.id
            GROUP BY cer.tipo_id, c.nombre
            ORDER BY c.nombre
        ");

        return response()->json($resultados);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/consultaCervezasPorGraduaciones",
     *      operationId="consultaCervezasPorGraduaciones",
     *      tags={"System"},
     *      summary="Consulta la cantidad de cervezas por graduaciones",
     *      description="Devuelve la cantidad de cervezas agrupadas por tipo",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="value", type="integer"),
     *                  @OA\Property(property="name", type="string"),
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
    public function consultaCervezasGraduaciones()
    {
        $resultados = DB::select("
        SELECT COUNT(DISTINCT cer.id) as value, g.nombre as name
        FROM cervezas as cer
        INNER JOIN graduaciones AS g ON cer.graduacion_id = g.id
        GROUP BY g.id, g.nombre order by g.nombre;
        
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



    /**
     * @OA\Get(
     *      path="/api/v1/consultaTablas2",
     *      operationId="consultaTablas2",
     *      tags={"System"},
     *      summary="Consulta los registros de las tablas",
     *      description="Devuelve el número de registros de las tablas específicas",
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="table_name", type="string"),
     *                  @OA\Property(property="record_count", type="integer"),
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
    public function consultaTablas2()
    {
        try {
            $tables = [
                'cervezas' => Cerveza::count(),
                'tipos' => Tipo::count(),
                'graduaciones' => Graduacion::count(),
                'colores' => Color::count(),
                'paises' => Pais::count(),
            ];

            return response()->json($tables);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
};
