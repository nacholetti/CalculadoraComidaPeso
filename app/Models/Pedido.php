<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['mesa'];

    public function comidas()
    {
        return $this->belongsToMany(Comida::class)
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
