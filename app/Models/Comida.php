<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comida extends Model
{
    // Campos que se pueden insertar masivamente
    protected $fillable = ['nombre', 'peso_total', 'precio_venta'];

    // Relación con ingredientes (muchos a muchos)
    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class)
                    ->withPivot('cantidad') // la cantidad usada de ese ingrediente
                    ->withTimestamps();
    }
}
