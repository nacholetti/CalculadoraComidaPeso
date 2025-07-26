<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GananciaController;

Route::get('/', [GananciaController::class, 'SimuladorGanancias']);
Route::post('/comidas/store', [GananciaController::class, 'guardadoAlimentos']);

Route::get('/ingredientes/create', [GananciaController::class, 'formularioIngredientes']);
Route::post('/ingredientes/store', [GananciaController::class, 'guardarIngrediente']);
Route::get('/', [GananciaController::class, 'SimuladorGanancias']);
Route::get('/stock', [GananciaController::class, 'mostrarStock']);
// web.php
Route::post('/stock/update', [GananciaController::class, 'actualizarStock']);

Route::get('/comidas/disponibles', [GananciaController::class, 'disponibles']);

