<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provincias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
    ];

    /**
     * Get the populations for the province.
     */
    public function poblaciones()
    {
        return $this->hasMany(Poblacion::class, 'provincia_cod', 'codigo');
    }
}
