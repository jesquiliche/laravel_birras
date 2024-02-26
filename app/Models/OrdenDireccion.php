<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDireccion extends Model
{
    use HasFactory;
    protected $table='orden_direcciones';
    protected $fillable=['nombre','apellidos','calle',
    'numero','escalera','piso','puerta',
    'poblacion','provincia','user_id','user_id',
    'orden_id','telefono'];
}