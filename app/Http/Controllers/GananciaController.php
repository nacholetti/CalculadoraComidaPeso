<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Ingrediente;

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

       return redirect('/')->with('success', 'Ingrediente Creado!');


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

    return redirect('/')->with('success', 'Comida guardada con ingredientes!');

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

public function formularioComidas() {
    $ingredientes = Ingrediente::all();
    return view('comidas.create', compact('ingredientes'));
}
public function calcularDisponibilidad()
{
    $comidas = Comida::with('ingredientes')->get();

    $resultados = [];

    foreach ($comidas as $comida) {
        $cantidadesPosibles = [];

        foreach ($comida->ingredientes as $ingrediente) {
            $stock = $ingrediente->stock ?? 0; // stock disponible
            $cantidadNecesaria = $ingrediente->pivot->cantidad; // cantidad que usa para 1 plato (kg, unidad, etc)

            if ($cantidadNecesaria > 0) {
                // Cuántos platos se pueden hacer con ese ingrediente
                $cantidadesPosibles[] = floor($stock / $cantidadNecesaria);
            } else {
                // Si cantidad necesaria 0, lo ignoramos (o asignamos un valor grande)
                $cantidadesPosibles[] = PHP_INT_MAX;
            }
        }

        // La cantidad máxima de platos que se pueden hacer es la mínima de esas cantidades
        $maxPlatos = !empty($cantidadesPosibles) ? min($cantidadesPosibles) : 0;

        $resultados[] = [
            'comida' => $comida->nombre,
            'max_platos' => $maxPlatos,
        ];
    }

    return view('disponibles_con_stock', ['comidas' => $comidas, 'disponiblesPorComida' => $resultados]);


}


// Mostrar formulario para crear bebida
public function formularioBebida()
{
    return view('bebidas'); // Tenés que crear esta vista
}

// Guardar bebida en la DB
public function guardarBebida(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100',
        'precio_venta' => 'required|numeric|min:0',
        'volumen_litros' => 'required|numeric|min:0',
    ]);

    Bebida::create([
        'nombre' => $request->nombre,
        'precio_venta' => $request->precio_venta,
        'volumen_litros' => $request->volumen_litros,
    ]);

    return redirect('/bebidas')->with('success', 'Bebida guardada correctamente!');
}

// Listar bebidas
public function listarBebidas()
{
    $bebidas = Bebida::all();
    return view('bebidas', compact('bebidas')); // También tenés que crear esta vista
}

public function verStock() {
    $bebidas = Bebida::all();
    return view('stock_bebidas', compact('bebidas'));
}
// Mostrar la vista con los platos (comidas)
public function formularioConsumo()
{
    $comidas = Comida::all(); // Usás el modelo Comida
    $bebidas = Bebida::all();

    return view('consumir', compact('comidas','bebidas'));
}

public function consumirPlato(Request $request)
{
    // Validar
    $request->validate([
        'plato_id'        => 'nullable|exists:comidas,id',
        'porciones'       => 'required_with:plato_id|integer|min:1',
        'bebida_id'       => 'nullable|exists:bebidas,id',
        'bebida_cantidad' => 'required_with:bebida_id|integer|min:1',
    ]);

    // Descontar ingredientes del plato
    if ($request->plato_id) {
        $plato = Comida::with('ingredientes')->findOrFail($request->plato_id);
        foreach ($plato->ingredientes as $ing) {
            $necesario = $ing->pivot->cantidad * $request->porciones;
            if ($ing->stock < $necesario) {
                return back()->with('error', "No hay suficiente stock de {$ing->nombre}");
            }
        }
        foreach ($plato->ingredientes as $ing) {
            $necesario = $ing->pivot->cantidad * $request->porciones;
            $ing->stock -= $necesario;
            $ing->save();
        }
    }

    // Descontar stock de bebida (si aplicable)
    if ($request->bebida_id) {
        $bebida = Bebida::findOrFail($request->bebida_id);
        // Asumimos que Bebida tiene una columna `stock`
        if ($bebida->stock < $request->bebida_cantidad) {
            return back()->with('error', "No hay suficiente stock de {$bebida->nombre}");
        }
        $bebida->stock -= $request->bebida_cantidad;
        $bebida->save();
    }

    return back()->with('success', 'Consumo registrado correctamente.');
}


 

 
// GET /productos/valorizar
public function valorizarProductosIndex(Request $request)
{
    $pct  = (float)$request->input('pct', 30);
    $modo = $request->input('modo', 'costo'); // 'costo' | 'precio'

    // COMIDAS
    $comidas = Comida::with('ingredientes')->get();
    $comidasCalculadas = $comidas->map(function ($c) use ($pct, $modo) {
        $costo = 0.0;
        foreach ($c->ingredientes as $ing) {
            $costo += (float)($ing->costo_unitario ?? 0) * (float)($ing->pivot->cantidad ?? 0);
        }
        $precioActual = (float)($c->precio_venta_kg ?? 0);

        $sugerido = $modo === 'precio'
            ? round($precioActual * (1 + $pct/100), 2)
            : round($costo * (1 + $pct/100), 2);

        $c->calc = [
            'costo'         => round($costo, 2),
            'precio_actual' => round($precioActual, 2),
            'sugerido'      => $sugerido,
            'ganancia'      => round($sugerido - $costo, 2),
            'delta'         => round($sugerido - $precioActual, 2),
        ];
        return $c;
    });

    // BEBIDAS
    $bebidas = Bebida::all();
    $bebidasCalculadas = $bebidas->map(function ($b) use ($pct) {
        $precioActual = (float)($b->precio_venta ?? 0);
        $sugerido     = round($precioActual * (1 + $pct/100), 2);
        $b->calc = [
            'costo'         => isset($b->costo_unitario) ? round((float)$b->costo_unitario, 2) : null,
            'precio_actual' => round($precioActual, 2),
            'sugerido'      => $sugerido,
            'ganancia'      => isset($b->costo_unitario) ? round($sugerido - (float)$b->costo_unitario, 2) : null,
            'delta'         => round($sugerido - $precioActual, 2),
        ];
        return $b;
    });

    // IMPORTANTE: pasar el resumen (puede ser null)
    $r = session('resumen');

    return view('valorizar_productos', compact('pct','modo','comidasCalculadas','bebidasCalculadas','r'));
}

public function valorizarProductosAplicar(Request $request)
{
    $request->validate([
        'pct' => 'required|numeric|min:0',
        'modo' => 'in:costo,precio',
        'seleccion_comidas' => 'nullable',
        'seleccion_bebidas' => 'nullable',
    ]);

    $pct  = (float)$request->input('pct', 30);
    $modo = $request->input('modo', 'costo');

    // Normalizar SIEMPRE a array de enteros
    $idsC = (array) $request->input('seleccion_comidas', []);
    $idsB = (array) $request->input('seleccion_bebidas', []);

    // Si los checkboxes no tenían value=id, pueden venir "on". Limpio todo lo no numérico.
    $idsC = array_values(array_filter(array_map('intval', $idsC), fn($v) => $v > 0));
    $idsB = array_values(array_filter(array_map('intval', $idsB), fn($v) => $v > 0));

    // (Opcional) Si querés exigir selección:
    // if (empty($idsC) && empty($idsB)) {
    //     return back()->with('error', 'No seleccionaste ningún producto.');
    // }

    $actC = []; $omitC = [];
    $actB = []; $omitB = [];

    DB::transaction(function () use ($pct, $modo, $idsC, $idsB, &$actC, &$omitC, &$actB, &$omitB) {
        // COMIDAS
        if (!empty($idsC)) {
            $comidas = Comida::with('ingredientes')->whereIn('id', $idsC)->get();
            foreach ($comidas as $c) {
                $actual = (float)($c->precio_venta_kg ?? 0);
                if ($modo === 'precio') {
                    $nuevo = round($actual * (1 + $pct/100), 2);
                } else {
                    $costo = 0.0;
                    foreach ($c->ingredientes as $ing) {
                        $costo += (float)($ing->costo_unitario ?? 0) * (float)($ing->pivot->cantidad ?? 0);
                    }
                    $nuevo = round($costo * (1 + $pct/100), 2);
                }

                if ($nuevo == $actual) {
                    $omitC[] = ['id'=>$c->id,'nombre'=>$c->nombre,'motivo'=>'Sin cambio'];
                } else {
                    $c->precio_venta_kg = $nuevo;
                    $c->save();
                    $actC[] = ['id'=>$c->id,'nombre'=>$c->nombre,'de'=>$actual,'a'=>$nuevo];
                }
            }
        }

        // BEBIDAS
        if (!empty($idsB)) {
            $bebidas = Bebida::whereIn('id', $idsB)->get();
            foreach ($bebidas as $b) {
                $actual = (float)($b->precio_venta ?? 0);
                $nuevo  = round($actual * (1 + $pct/100), 2);

                if ($nuevo == $actual) {
                    $omitB[] = ['id'=>$b->id,'nombre'=>$b->nombre,'motivo'=>'Sin cambio'];
                } else {
                    $b->precio_venta = $nuevo;
                    $b->save();
                    $actB[] = ['id'=>$b->id,'nombre'=>$b->nombre,'de'=>$actual,'a'=>$nuevo];
                }
            }
        }
    });

    $resumen = [
        'pct'  => $pct,
        'modo' => $modo,
        'comidas' => [
            'actualizados' => $actC,
            'omitidos'     => $omitC,
        ],
        'bebidas' => [
            'actualizados' => $actB,
            'omitidos'     => $omitB,
        ],
        'total_actualizados' => count($actC) + count($actB),
    ];

    return redirect('/productos/valorizar')
        ->with('success', "Precios procesados con el {$pct}%.")
        ->with('resumen', $resumen);
}


}


