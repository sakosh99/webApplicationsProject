<?php

namespace App\Http\Requests\auth;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail('Your current password does not matches with the password you provided, Please try again');
                }
            }],
            'new_password' => ['required', 'min:6', 'confirmed', function ($attribute, $value, $fail) {
                if (Hash::check($value, Auth::user()->password)) {
                    return $fail('New Password cannot be same as your current password, Please choose a different password');
                }
            }],
            'new_password_confirmation' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'newPassword.confirmed' => 'The Password confirmation does not match'
        ];
    }
}
