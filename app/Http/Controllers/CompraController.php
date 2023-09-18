<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Compra;
use App\Models\Producto;
use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return ApiResponse::success('Listado de compras',200,Compra::all());
        }catch(Exception $e){
            return ApiResponse::error('Error al listar las compras',500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function realizarCompra(Request $request)
    {
        try{
            
            //Validar la lista de productos ingresada
            $request->validate([
                'productos'=>'required|array|min:1',
                'productos.*.producto_id'=>'required|integer|exists:productos,id',
                'productos.*.quantity'=>'required|integer|min:1'
            ]);
           
            //Verificar que en la petición no hayan productos repetidos
            $productosIds = array_column($request->productos,'producto_id');
            if(count($productosIds) !== count(array_unique($productosIds))){
                return ApiResponse::error('No se permiten productos duplicados para la compra',400);
            }

            //Iniciar nueva compra 
            $compra = new Compra();
            $compra->subtotal=0; //Iniciar en 0 el subtotal
            $compra->total=0; //Iniciar en 0 el subtotal
            $compra->save();

            //Recorremos la lista de productos a comprar 
            foreach($request->productos as $productoData){

                //Obtener el producto y verificar su disponibilidad 
                $producto = Producto::findOrFail($productoData['producto_id']);
                if($producto->quantity_available < $productoData['quantity']){
                    return ApiResponse::error('Stock insuficiente para el producto '.$producto->name,400);
                }
                
                //Después de verificar que haya stock se actualiza el stock de cada producto
                $producto->quantity_available -= $productoData['quantity'];
                $producto->save(); 

                //Calcular el subtotal para este producto 
                $subtotalProducto = $producto->price * $productoData['quantity'];

                //Crear una entrada a la tabla pivot 
                $compra->productos()->attach($producto->id,[
                    'price'=>$producto->price,
                    'quantity'=>$productoData['quantity'],
                    'subtotal'=>$subtotalProducto
                ]);

                //Actualizar el total de la compra 
                $compra->subtotal += $subtotalProducto ; 
            }

            //Calcular el total de la compra, aquí se incluye el igv
            $compra->total = $compra->subtotal + (0.18*$compra->subtotal);

            //Gurdar la compra 
            $compra->save();
            return ApiResponse::success('Compra realizada satisfactoriamente',201,$compra);

        }catch(ValidationException $e){
            $errors = $e->validator->errors()->toArray();
            return ApiResponse::error('Datos inválidos en la lista de productos',400,$errors);

        }catch(Exception $e){
            return ApiResponse::error('Error inesperado',500,$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function compraById($id)
    {
        try{
            return ApiResponse::success('Compra encontrada exitosamente',200,Compra::findOrFail($id));
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Compra no encontrada',404);
        }
    }

    public function buyByIdWithProducts($id)
    {
        $buyWithProducts = Compra::with('productos')->findOrFail($id);
        
        try{
            return ApiResponse::success('Compra encontrada exitosamente',200,$buyWithProducts);

        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Compra no encontrada',404);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteBuy($id)
    {
        try{

            Compra::findOrFile($id)->delete();

        }catch(ModelNotFoundException $e){
            
            return ApiResponse::error('Compra no encontrada',404);
        }
    }
}
