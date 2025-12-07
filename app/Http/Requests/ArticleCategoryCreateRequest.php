<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleCategoryCreateRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'short_name' => 'nullable|string|max:100',
            'color' => 'required|string|size:7|regex:/^#([A-Fa-f0-9]{6})$/',
            'show_head_flg' => 'nullable|integer|in:0,1',
            'order' => 'required|integer|unique:article_categories,order',
            'special_flg' => 'nullable|integer|in:0,1',
        ];
    }

    public function messages(){
        return [
            'name.required' => __('article_category_name.required'),
            'short_name.max' => __('article_category_short_name.max'),
            'color.required' => __('article_category_color.required'),
            'color.regex' => __('article_category_color.regex'),
            'order.required' => __('article_category_order.required'),
            'order.unique' => __('article_category_sort.duplicate')
        ];
    }
}
