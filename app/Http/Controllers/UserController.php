<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\InsertUserRequest;
use App\Http\Requests\Users\LoginUserRequest;
use App\Http\Requests\Users\updateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Método para  listar todos los usuario 
    public function getAllUsers(){
        $users = User::paginate(5);
        return ApiResponse::success('Listado de usuarios',200,$users);
    }
    
    //Método para listar un solo usuario 
    public function getUserById($id){
        if(!isset($id)) return ApiResponse::error('Debe seleccionar el usuario',404);
        
        $user = User::find($id);
        if(!isset($user)) return ApiResponse::error('Usuario no encontrado',404);

        return ApiResponse::success('Usuario encontrado con éxito',200, $user);
    }

    //Método para crear un usuario 
    public function createUser(InsertUserRequest $request){
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password) 
        ]);

        return ApiResponse::success('Usuario creado exitosamente',201);
    }

    //Método para inicar sesión 
    public function login(LoginUserRequest $request){
        $user = User::where('email',$request->email)->first();

        if(!isset($user)) return ApiResponse::error('Usuario no encontrado',404);

        if(Hash::check($request->password, $user->password)){//contraseña correcta
            $token = $user->createToken('auth_token')->plainTextToken;
            return ApiResponse::success('Bienvenido al sistema',200,$token);

        }else{
            return ApiResponse::error('La contraseña es incorrecta',404);
        }
    }

    //Método para actualizar un usuario 
    public function updateUser(updateUserRequest $request, $id){
        
        $user = User::find($id); 

        if(!isset($user)) return ApiResponse::error('Usuario no encontrado',404);

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        return ApiResponse::error('Usuario actualizado con éxito',200,$user);
    }

    //Método para eliminar un usuario 
    public function deleteUser($id){
        if(!isset($id)) return ApiResponse::error('Debe elegir un usuario',400);

        $user = User::find($id);

        if(isset($user)){
            $user->delete();
            return ApiResponse::success('Usuario borrado con éxito',200);

        }else{
            return ApiResponse::error('Usuario no encontrado',404);
        }
    }
}
