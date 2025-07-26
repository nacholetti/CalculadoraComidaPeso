<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    // Campos que se pueden insertar masivamente
    protected $fillable = ['nombre', 'unidad', 'costo_unitario'];

    // Si no usás created_at y updated_at, podés desactivar los timestamps (opcional)
    // public $timestamps = false;

    // Relación con comidas (muchos a muchos)
    public function comidas()
    {
        return $this->belongsToMany(Comida::class)
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
