<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bebida extends Model
{
    protected $table = 'bebidas'; // nombre de tu tabla en la DB

    protected $fillable = [
        'nombre',
        'precio_venta',
        'volumen_litros'
    ];
}
