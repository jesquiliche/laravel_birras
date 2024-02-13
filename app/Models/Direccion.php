<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = [
        'calle',
        'numero',
        'escalera',
        'piso',
        'puerta',
        'poblacion_id',
        'provincia_id',
        'user_id',
    ];

    public function poblacion()
    {
        return $this->belongsTo(Poblacion::class, 'poblacion_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
