<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "password" => ['required' , Password::min(8)->mixedCase()->numbers() , 'confirmed'],
            "token" => 'required',
            "email" => 'required'
        ];
    }

    public function messages()
    {
        return [
            "password.required" => __("user_password.required"),
            "password.confirmed" => __("user_password.confirmed"),
            "password.mixed" =>  __("password_mixed"),
            "password.numbers" => __("password_numbers"),
            "email.required" => __("email.required"),
            "token.required" => __("token.required")
        ];
    }
}
