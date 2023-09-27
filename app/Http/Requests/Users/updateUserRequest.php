<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class updateUserRequest extends BaseRequest
{

    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        $user_id = $this->route('id'); //Obtiene el id que pasa como parámetro
        return [
            'name'=>'required|min:5|max:256',
            'email'=>['required','email','max:256',Rule::unique('users')->ignore($user_id)],
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
