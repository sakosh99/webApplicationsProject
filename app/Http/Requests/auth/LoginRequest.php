<?php

namespace App\Http\Requests\auth;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use ApiResponser;
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
            'user_name'     => 'required|string',
            'password'      => 'required|string|min:6'
        ];
    }
}
