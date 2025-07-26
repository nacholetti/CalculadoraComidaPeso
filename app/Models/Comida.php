<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comida extends Model
{
    public $timestamps = false;  // <--- Agregar esto si no querés timestamps

    protected $fillable = ['nombre', 'precio_venta_kg'];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class)
                    ->withPivot('cantidad');
                    
    }
}
