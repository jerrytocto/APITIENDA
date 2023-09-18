<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Categoria;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    /**
     * Lista todas las categorías del sistema 
     */
    public function index()
    {
        try{

            return ApiResponse::success('Lista de categorías',200,Categoria::all());

        }catch(Exception $e){

            return ApiResponse::error('Error al listar las categorías',500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        try{
            $request->validate([
                'name'=>'required|unique:categorias'
            ]);
            
            $categoria = Categoria::create($request->all());
            return ApiResponse::success('Categoría registrada con éxito','201',$categoria);

        }catch(ValidationException $e){
            return ApiResponse::error('Error de validación: '.$e->getMessage(),422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getCategoryById($id)
    {
        try{

            $category = Categoria::findOrFail($id);
            return ApiResponse::success('Categoría encontrada exitosamente',200,$category);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Categoria no encontrada',404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{

            $category = Categoria::findOrFail($id);
            $request->validate([
                'name'=>['required',Rule::unique('categorias')->ignore($category)]
            ]);
            $category->update($request->all());
            return ApiResponse::success('Categoria actualizada exitosamente',200,$category);

        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Categoría no encontrada: '.$e->getMessage(),404);

        }catch(Exception $e){
            return ApiResponse::error('Error al actualizar: '.$e->getMessage(),422);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try{
            $category = Categoria::findOrFail($id);
            $category->delete();
            return ApiResponse::success('Categoría eliminada exitosamente',200);

        }catch(ModelNotFoundException $e){
            
            return ApiResponse::error('Categoría no encotrada',404);
        }
    }

    public function productsByCategory($id){
        try{

            $categoria= Categoria::with('productos')->findOrFail($id);
            return ApiResponse::success('Categoría con sus productos',200,$categoria);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Categoría no encontrada',404);
        }
    }
}
