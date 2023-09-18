<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Marca;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MarcaController extends Controller
{
    //Método para listar todas las marcas
    public function index()
    {
        try{

            return ApiResponse::success('Listado de Marcas',200,Marca::all());

        }catch(Exception $e){

            return ApiResponse::error('Error al listar las marcas',500);
        }
    }

    //Métdo para crear una marca 
    public function create(Request $request){
        try{

            $request->validate([
                'name'=>'required|unique:marcas'
            ]);

            $marca = Marca::create($request->all());
            return ApiResponse::success('Marca creada exitosamente',201,$marca);

        }catch(ValidationException $e){

            return ApiResponse::error('Error de validación: '.$e->getMessage(),422);
        }
    }

    //Método para listar una sola marca por id 
    public function getMarcaById($id){

        try{
            
            return ApiResponse::success('Marca encontrada exitosamente',200,Marca::findOrFail($id));

        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Marca no encontrada',404);
        }
    }

    //Método para actualizar una marca 
    public function update(Request $request, $id){
        try{

            $brand = Marca::find($id);
            $request->validate([
                'name'=>['required',Rule::unique('marcas')->ignore($brand)]
            ]);
            $brand->update($request->all());
            return ApiResponse::success('Marca encontrada exitosamente',200,$brand);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Marca no encotrada: '.$e->getMessage(),404);

        }catch(Exception $e){

            return ApiResponse::error('Error al actualizar la marca: '.$e->getMessage(),422);
        }
    }

    //Función para eliminar una marca 
    public function delete($id){
        try{

            Marca::findOrFail($id)->delete();
            return ApiResponse::success('Marca eliminada exitosamente',200);

        }catch(ModelNotFoundException $e){
            
            return ApiResponse::error('Marca no encontrada',404);
        }
    }

    //Función para listar todas las marcas con sus productos 
    public function productsByBrand($id){

        try{

            $brand = Marca::with('productos')->findOrFail($id);
            return ApiResponse::success('Marca con sus productos',200,$brand);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Marca no encontrada',404);
        }
    }
}
