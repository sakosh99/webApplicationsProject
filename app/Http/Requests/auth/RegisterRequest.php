<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Lang;

class RegisterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name'         => 'required|string',
            'email'             => 'required|string|unique:users,email',
            'user_name'         => 'required|string|unique:users,user_name',
            'password'          => 'required|string|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'The Password confirmation does not match',
            'user_name.unique' => 'User name has already been taken',
            'email.unique' => 'Email has already been taken'
        ];
    }
}
