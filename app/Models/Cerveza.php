<?php

namespace App\Models;

use Faker\Provider\fr_CH\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cerveza extends Model
{
    use HasFactory;
    protected $table = 'cervezas'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'color_id',
        'graduacion_id',
        'tipo_id',
        'pais_id',
        'novedad',
        'oferta',
        'precio', 
        'foto',
        'marca'
    ];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function graduacion()
    {
        return $this->belongsTo(Graduacion::class, 'graduacion_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }


}
