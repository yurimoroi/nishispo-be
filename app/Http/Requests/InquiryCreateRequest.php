<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryCreateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'body' => 'required|string',
            'inquiry_type_id' => 'required|exists:inquiry_types,id', // Assuming you have InquiryType model
            'reply' => 'nullable|string', // Make sure 'reply' is included, and it's optional if needed
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('inquiry_name.required'),
            'email.required' => __('inquiry_email_address.required'),
            'email.email' => __('inquiry_email_address.email'),
            'body.required' => __('inquiry_content.required')
        ];
    }
}
