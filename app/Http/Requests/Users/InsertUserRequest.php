<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class InsertUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'name'=>'required|min:5|max:256',
            'email'=>'required|email|max:256|unique:users,email',
            'password'=>'required|min:5'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "EL nombre es obligatorio",
            'name.min' => "Tamaño del nombre debe ser mayor que 5",
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El correo es incorrecto',
            'email.unique' => 'El correo electrónico ya existe',
            'password.required' => 'La contraseña es obligatora ',
            'password.min' => 'La contraseña debe tener más de 5 caracteres '
        ];
    }
}
