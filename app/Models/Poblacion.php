<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poblacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'poblaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'provincia_cod',
    ];

    /**
     * Get the province that owns the population.
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_cod', 'codigo');
    }
}
