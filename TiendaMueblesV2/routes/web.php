<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PreferenciasController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\GaleriaController;

// Página Principal
Route::get('/', [PrincipalController::class, 'index'])->name('principal');

// Login (Sesiones):
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');
Route::get('/register', [App\Http\Controllers\LoginController::class, 'showRegister'])->name('register.show');
Route::post('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register.store');

// Preferencias (Cookies)
Route::get('/preferencias', [PreferenciasController::class, 'edit'])->name('preferencias.edit');
Route::post('/preferencias', [PreferenciasController::class, 'update'])->name('preferencias.update');

// Catálogo: categorías
Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');
Route::get('/categoria/{id}', [CategoriasController::class, 'show'])->name('categorias.show');

// Catálogo: productos (listado + detalle)
Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
Route::get('/producto/{id}', [ProductosController::class, 'show'])->name('productos.show');

// Carrito (Base de datos):
Route::get('/carrito', [CarritoController::class, 'show'])->name('carrito.show');
Route::post('/carrito/add/{producto}', [CarritoController::class, 'add'])->name('carrito.add');
Route::post('/carrito/aumentar/{item}', [CarritoController::class, 'aumentar'])->name('carrito.aumentar');
Route::post('/carrito/disminuir/{item}', [CarritoController::class, 'disminuir'])->name('carrito.disminuir');
Route::post('/carrito/remove/{item}', [CarritoController::class, 'remove'])->name('carrito.remove');
Route::post('/carrito/clear', [CarritoController::class, 'clear'])->name('carrito.clear');
Route::post('/carrito/comprar', [CarritoController::class, 'comprar'])->name('carrito.comprar');

// Panel de Administración (Solo usuario rol ADMIN)
Route::get('/admin', [AdministracionController::class, 'index'])->name('administracion');

// Categorías (CRUD)
Route::resource('categorias', CategoriasController::class);

// Nombres generados:
// categorias.index|create|store|show|edit|update|destroy
// Productos (CRUD)
Route::resource('productos', ProductosController::class);

// Galería de Productos
Route::post('productos/{producto}/galeria', [GaleriaController::class, 'store'])->name('productos.galeria.store');
Route::delete('productos/{producto}/galeria/{galeria}', [GaleriaController::class, 'destroy'])->name('productos.galeria.destroy');
Route::post('productos/{producto}/galeria/{galeria}/principal', [GaleriaController::class, 'setPrincipal'])->name('productos.galeria.principal');
Route::post('productos/{producto}/galeria/reordenar', [GaleriaController::class, 'reordenar'])->name('productos.galeria.reordenar');
