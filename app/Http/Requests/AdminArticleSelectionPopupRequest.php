<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminArticleSelectionPopupRequest extends FormRequest
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
            'filter.status' => 'nullable|string',
            'filter.search' => 'nullable|string',
            'filter.categories' => 'nullable|array',
            
            'perPage' => 'nullable|integer|min:1',
        ];
    }


    public function messages()
    {
        return [
            'filter.dates.string' => __('admin_article_list.dates_string'),
            'perPage.integer' => __('admin_article_list.perPage.integer'),
        ];
    }
}
