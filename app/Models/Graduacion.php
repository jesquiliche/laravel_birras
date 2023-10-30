<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduacion extends Model
{
    use HasFactory;
    protected $table='graduaciones';
    protected $fillable=['nombre'];
   
    public function graduaciones()
    {
        return $this->hasMany(Cervezas::class);
    }
}
