<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('categorias',[CategoriaController::class,'index']);
Route::post('categorias',[CategoriaController::class,'create']);
Route::get('categorias/{id}',[CategoriaController::class,'getCategoryById']);
Route::put('categorias/{id}',[CategoriaController::class,'update']);
Route::delete('categorias/{id}',[CategoriaController::class,'delete']);
Route::get('categorias/{id}/productos',[CategoriaController::class,'productsByCategory']);

Route::get('marcas',[MarcaController::class,'index']);
Route::post('marcas',[MarcaController::class,'create']);
Route::get('marcas/{id}',[MarcaController::class,'getMarcaById']);
Route::put('marcas/{id}',[MarcaController::class,'update']);
Route::delete('marcas/{id}',[MarcaController::class,'delete']);
Route::get('marcas/{id}/productos',[MarcaController::class,'productsByBrand']);

Route::get('productos',[ProductoController::class,'index']);
Route::post('productos',[ProductoController::class,'create']);
Route::get('productos/{id}',[ProductoController::class,'getProductoById']);
Route::put('productos/{id}',[ProductoController::class,'update']);
Route::delete('productos/{id}',[ProductoController::class,'destroy']);

Route::get('compras',[CompraController::class,'index']);
Route::get('compras/{id}',[CompraController::class,'compraById']);
Route::get('compras/{id}/productos',[CompraController::class,'buyByIdWithProducts']);
Route::post('compras',[CompraController::class,'realizarCompra']);
Route::delete('compras/{id}',[CompraController::class,'deleteBuy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});