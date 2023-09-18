<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Producto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    public function index()
    {
        try{
            $produts = Producto::with('marca','categoria')->get();
            return ApiResponse::success('Lista de usuarios ',200, $produts);

        }catch(Exception $e){

            return ApiResponse::error('Error al listar los productos',500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        try{

            $request->validate([
                'name'=>'required|unique:productos',
                'price'=>'required|numeric|between:0,999999.99',
                'quantity_available'=>'required|integer',
                'categoria_id'=>'required|exists:categorias,id',
                'marca_id'=>'required|exists:marcas,id'
            ]);
            $product = Producto::create($request->all());
            return ApiResponse::success('Usuario registrado exitosamente',201,$product);

        }catch(ValidationException $e){

            $errors = $e->validator->errors()->toArray();
            return ApiResponse::error('Error al insertar un producto: ',422,$errors);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getProductoById($id)
    {
        try{

            $product = Producto::with('marca','categoria')->findOrFail($id);
            return ApiResponse::success('Producto encontrado',200,$product);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Producto no encontrado',404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $product = Producto::findOrFail($id);
            $request->validate([
                'name'=>['required',Rule::unique('productos')->ignore($product)],
                'price'=>'required|numeric|between:0,999999.99',
                'quantity_available'=>'required|integer',
                'categoria_id'=>'required|exists:categorias,id',
                'marca_id'=>'required|exists:marcas,id'
            ]);
            $product->update($request->all());
            return ApiResponse::success('Producto actualizado exitosamente',200,$product);

        }catch(ModelNotFoundException $e){
            
            return ApiResponse::error('Producto no econtrado',404);

        }catch(ValidationException $e){
            $errors = $e->validator->errors()->toArray();
            return ApiResponse::error('Error al actualizar un producto: ',422,$errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{

            Producto::findOrFail($id)->delete();
            return ApiResponse::success('Producto eliminado exitosamente',200);
            
        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Producto no encontrado',404);
        }
    }
}