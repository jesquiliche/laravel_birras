<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $table='colores';
    public function cervezas()
    {
        return $this->hasMany(Cerveza::class);
    }
}
