<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyInfoRequest extends FormRequest
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
            'terms' => 'nullable|string',
            'about_service' => 'nullable|string',
            'about_company' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'about_report' => 'nullable|string',
            'ad' => 'nullable|string',
            'reporter_editor' => 'nullable|string',
            'about_publish_content' => 'nullable|string',
        ];
    }
}
