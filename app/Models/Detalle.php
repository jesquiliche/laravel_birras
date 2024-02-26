<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    use HasFactory;
    protected $table='detalles';
    protected $fillable=['orden_id','cerveza_id','cantidad'];
    
}
