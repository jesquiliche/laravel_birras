<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cerveza;

class Graduacion extends Model
{
    use HasFactory;
    protected $table='graduaciones';
    protected $fillable=['nombre'];
   
    public function cervezas()
    {
        return $this->hasMany(Cerveza::class);
    }
}
