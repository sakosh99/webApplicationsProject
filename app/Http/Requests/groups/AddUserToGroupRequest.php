<?php

namespace App\Http\Requests\groups;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToGroupRequest extends FormRequest
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
            'group_id' => 'numeric|required',
            'user_id'  => 'numeric|required',
        ];
    }
}
