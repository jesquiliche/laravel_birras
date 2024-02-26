<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $table='ordenes';
    protected $fillable=['total','subtotal','iva','pagado','entregado','user_id'];
   
}
