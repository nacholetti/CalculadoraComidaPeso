<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GananciaController; // <- tu controlador principal

// ---------- INICIO / HOME ----------
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ---------- ZONA PÚBLICA (Clientes) ----------
Route::get('/tienda', [GananciaController::class, 'tiendaCliente'])->name('tienda.index');
Route::post('/checkout', [GananciaController::class, 'checkout'])->name('tienda.checkout');
Route::get('/checkout/resumen', [GananciaController::class, 'checkoutResumen'])->name('tienda.checkout.resumen');

// ---------- DASHBOARD (para usuarios logueados con Breeze) ----------
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------- ZONA PROTEGIDA (solo usuarios logueados) ----------
Route::middleware('auth')->group(function () {

    // ---- INGREDIENTES ----
    Route::get('/ingredientes/stock', [GananciaController::class, 'stockIngredientes'])->name('ingredientes.stock');
    Route::post('/ingredientes/stock', [GananciaController::class, 'actualizarStockIngredientes'])->name('ingredientes.stock.update');
    Route::get('/ingredientes/create', [GananciaController::class, 'createIngrediente'])->name('ingredientes.create');
    Route::post('/ingredientes', [GananciaController::class, 'storeIngrediente'])->name('ingredientes.store');

    // ---- COMIDAS ----
    Route::get('/comidas/disponibles', [GananciaController::class, 'calcularDisponibilidad'])->name('comidas.disponibles');
    Route::get('/comidas/create', [GananciaController::class, 'createComida'])->name('comidas.create');
    Route::post('/comidas', [GananciaController::class, 'storeComida'])->name('comidas.store');

    // ---- BEBIDAS ----
    Route::get('/bebidas', [GananciaController::class, 'indexBebidas'])->name('bebidas.index');
    Route::get('/bebidas/stock', [GananciaController::class, 'stockBebidas'])->name('bebidas.stock');
    Route::post('/bebidas/stock', [GananciaController::class, 'actualizarStockBebidas'])->name('bebidas.stock.update');
    Route::get('/bebidas/create', [GananciaController::class, 'createBebida'])->name('bebidas.create');
    Route::post('/bebidas', [GananciaController::class, 'storeBebida'])->name('bebidas.store');

    // ---- VALORIZACIÓN ----
    Route::get('/productos/valorizar', [GananciaController::class, 'valorizarProductos'])->name('productos.valorizar');
    Route::post('/productos/valorizar', [GananciaController::class, 'valorizarProductosAplicar'])->name('productos.valorizar.aplicar');

    // ---- PERFIL (Breeze) ----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// rutas de Breeze (login, registro, etc.)
require __DIR__.'/auth.php';
