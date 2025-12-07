<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLinkRequest extends FormRequest
{
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
            'provider.required' => __('provider.required'),
            'provider_id.required' => __('provider_id.required'),
            'provider.string' => __('provider.string'),
            'provider_id.string' => __('provider_id.string'),
        ];
    }
}
