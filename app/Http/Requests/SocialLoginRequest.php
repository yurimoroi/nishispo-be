<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
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
            'provider' => 'required|string',
            'provider_id' => 'required|string',
        ];
    }

     /**
     * Get the custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'provider.required' => 'The provider field is required.',
            'provider_id.required' => 'The provider ID field is required.',
            'provider.string' => 'The provider must be a valid string.',
            'provider_id.string' => 'The provider ID must be a valid string.',
        ];
    }
}
