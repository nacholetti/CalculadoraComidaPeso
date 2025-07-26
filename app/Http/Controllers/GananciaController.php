<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;
use App\Models\Comida;
class GananciaController
{
   public function SimuladorGanancias(){

         $ingredientes = Ingrediente::all(); // ← esto trae todos los ingredientes de la BD
        return view('calculadoraVista')->with('ingredientes', $ingredientes);

   }

   
    public function formularioIngredientes() {
        return view('ingredientesCreate');
    }

    public function guardarIngrediente(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'unidad' => 'required|in:kg,unidad,litro',
            'costo_unitario' => 'required|numeric|min:0'
        ]);

        Ingrediente::create([
            'nombre' => $request->nombre,
            'unidad' => $request->unidad,
            'costo_unitario' => $request->costo_unitario,
        ]);

        return redirect('/ingredientes/create');

    }


 public function guardadoAlimentos(Request $request){
    $request->validate([
        'nombre' => 'required|string|max:100',
        'precio_venta_kg' => 'required|numeric|min:0',
        'ingredientes' => 'required|array',
        'ingredientes.*.id' => 'required|exists:ingredientes,id',
        'ingredientes.*.cantidad' => 'required|numeric|min:0',
    ]);

    // Crear la comida
    $comida = Comida::create([
        'nombre' => $request->nombre,
        'precio_venta_kg' => $request->precio_venta_kg,
    ]);

    // Armar el array para sync con la tabla pivote
    $ingredientesSync = [];
    foreach ($request->ingredientes as $ingrediente) {
        $ingredientesSync[$ingrediente['id']] = ['cantidad' => $ingrediente['cantidad']];
    }

    // Guardar la relación ingredientes con cantidades
    $comida->ingredientes()->sync($ingredientesSync);

    return redirect('/comidas/create')->with('success', 'Comida guardada con ingredientes!');
}



public function mostrarStock()
{
    $ingredientes = Ingrediente::all();
    return view('AlimentosStock', compact('ingredientes'));
}

// GananciaController.php

public function actualizarStock(Request $request)
{
    $stocks = $request->input('stock', []);

    foreach ($stocks as $ingredienteId => $nuevoStock) {

        $ingrediente = Ingrediente::find($ingredienteId);

        if ($ingrediente) {
            $ingrediente->stock = $nuevoStock;
            $ingrediente->save();
        }
    }

    return redirect('/stock')->with('success', 'Stock actualizado correctamente.');


}

public function disponibles()
{
    $comidas = Comida::with('ingredientes')->get();
    
    return view('disponibles', compact('comidas'));
}


/*FALTA BOTON VER COMIDAS DISPONIBLES, INGREDIENTES NECESARIOS PARA CADA PLATO Y CALCULAR PARA CUANTOS PLATOS ALCANZA EL STOCK, SI NO ALCANZA EL STOCK DAR LA POSIBLIDAD DE ACTUALIZAR EL <STOCK class="
PASO 2: alertar si alcanza para 4 platos o menos toda la fila en rojo, si hay mas de 15 disponibles en azul, entre 15 y 4 amarillo naranja"></STOCK> */

}
