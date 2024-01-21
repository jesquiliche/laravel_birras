<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    protected $table='paises';
    protected $fillable=['nombre','descripcion'];
   
    public function cervezas()
    {
        return $this->hasMany(Cerveza::class);
    }
}

