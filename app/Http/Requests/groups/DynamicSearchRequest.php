<?php

namespace App\Http\Requests\groups;

use Illuminate\Foundation\Http\FormRequest;

class DynamicSearchRequest extends FormRequest
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
            'filter' => 'required|in:published,joined,all'
        ];
    }
}
