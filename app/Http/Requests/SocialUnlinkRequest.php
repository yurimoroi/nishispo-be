<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialUnlinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'provider' => 'required|string',
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
            'provider.required' => __('provice.required'),
            'provider.string' => __('provider.string'),
        ];
    }
}
