<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GananciaController;



Route::post('/comidas/store', [GananciaController::class, 'guardadoAlimentos']);

Route::get('/ingredientes/create', [GananciaController::class, 'formularioIngredientes']);
Route::post('/ingredientes/store', [GananciaController::class, 'guardarIngrediente']);
Route::get('/', [GananciaController::class, 'SimuladorGanancias']);
Route::get('/stock', [GananciaController::class, 'mostrarStock']);
// web.php
Route::post('/stock/update', [GananciaController::class, 'actualizarStock']);

Route::get('/comidas/disponibles', [GananciaController::class, 'disponibles']);

Route::get('/comidas/create', [GananciaController::class, 'formularioComidas']);
Route::post('/comidas/store', [GananciaController::class, 'guardadoAlimentos'])->name('comidas.store');
Route::get('/comidas/disponibles_con_stock', [GananciaController::class, 'calcularDisponibilidad']);

Route::get('/bebidas/create', [GananciaController::class, 'formularioBebida']);
Route::post('/bebidas/store', [GananciaController::class, 'guardarBebida']);
Route::get('/bebidas', [GananciaController::class, 'listarBebidas']);


Route::get('/bebidas/stock', [GananciaController::class, 'verStock'])->name('bebidas.stock');

// Mostrar formulario para consumir platos
Route::get('/consumir', [GananciaController::class, 'formularioConsumo']);

// Procesar la acción de consumir
Route::post('/consumir', [GananciaController::class, 'consumirPlato']);


Route::get('/pedidos',      [GananciaController::class, 'formularioPedidos']);
Route::post('/pedidos',     [GananciaController::class, 'guardarPedido']);
Route::delete('/pedidos/{id}', [GananciaController::class, 'cancelarPedido']);


// Valorización de comidas (+30%)
Route::get('/comidas/valorizar', [GananciaController::class, 'valorizarIndex']);
Route::post('/comidas/valorizar', [GananciaController::class, 'valorizarAplicar']);

Route::get('/productos/valorizar',  [GananciaController::class, 'valorizarProductosIndex']);
Route::post('/productos/valorizar', [GananciaController::class, 'valorizarProductosAplicar']);


// routes/web.php
Route::get('/tienda', [GananciaController::class, 'vistaCliente'])->name('tienda.cliente');
Route::post('/checkout', [GananciaController::class, 'checkoutStore'])
    ->name('checkout.store');

    Route::post('/checkout', [GananciaController::class, 'checkout'])->name('checkout.store');
