<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContributorApplyRequest extends FormRequest
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
            'contributor_name' => 'required|string|max:100',
            'rakuten_id' => 'string|nullable'
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'contributor_name.required' => __('user_contributor_name.required'),
            'contributor_name.max' => __('user_contributor_name.max')
        ];
    }
}
