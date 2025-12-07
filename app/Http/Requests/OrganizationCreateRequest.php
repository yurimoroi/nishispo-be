<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationCreateRequest extends FormRequest
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1048',
            'name' => 'required|max:100|min:3',
            'representative_name' => 'required|max:100|min:3',
            'tel_number' => 'required|string|max:30',
            'email' => 'required|email|unique:organizations,email',
            'activity_description' => 'nullable|string',
            'birthyear_viewing_flg' => 'boolean',
            'birthdate_viewing_flg' => 'boolean',
            'postal_code_viewing_flg' => 'boolean',
            'address_viewing_flg' => 'boolean',
            'phone_number_viewing_flg' => 'boolean',
            'mobile_phone_number_viewing_flg' => 'boolean',
            'email_viewing_flg' =>  'boolean',
            "user_administrators" => "required|array",
            "user_administrators.*" => "string|exists:users,id",
        ];
    }
}
