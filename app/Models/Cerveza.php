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
        'pais_id'
    ];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

}
