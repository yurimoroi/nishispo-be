<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyPostLimitUpdateRequest extends FormRequest
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
            'organization_member_post_limit' => 'required|numeric|between:1,100',
            'organization_post_limit' => 'required|numeric|between:1,100',
            'post_limit' => 'required|numeric|between:1,100'
        ];
    }

    public function messages(): array
{
    return [
        'organization_member_post_limit.required' => __('organization_member.required'),
        'organization_member_post_limit.numeric' => __('organization_member.numeric'),
        'organization_member_post_limit.between' => __('organization_member.between'),

        'organization_post_limit.required' => __('organization.required'),
        'organization_post_limit.numeric' => __('organization.numeric'),
        'organization_post_limit.between' => __('organization.between'),

        'post_limit.required' => __('post_limit.required'),
        'post_limit.numeric' => __('post_limit.numeric'),
        'post_limit.between' => __('post_limit.between'),
    ];
}
}
