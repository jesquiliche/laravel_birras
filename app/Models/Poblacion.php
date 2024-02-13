<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poblacion extends Model
{
    protected $table="poblaciones";
    protected $fillable = ['codigo','nombre'];
    use HasFactory;
}
