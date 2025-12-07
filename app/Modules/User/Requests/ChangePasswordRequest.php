<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "old_password" => 'required',
            "password" => ['required' , Password::min(8)->mixedCase()->numbers() , 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            "old_password.required" => __('user_old_password.required'),
            "password.required" => __("user_password.required"),
            "password.confirmed" => __("user_password.confirmed"),
            "password.mixed" =>  __("password_mixed"),
            "password.numbers" => __("password_numbers")
        ];
    }
}
