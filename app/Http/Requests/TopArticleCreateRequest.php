<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TopArticleCreateRequest extends FormRequest
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
            'article_id' => 'required',
            'order' => [
                'required',
                Rule::unique('top_articles', 'order')->whereNull('deleted_at'),
            ],
            'published_at' => 'required|date',
            'publish_ended_at' => [
                'nullable',
                'date',
                'date_format:Y-m-d H:i:s',
                Rule::requiredIf(fn() => request('publish_ended_at') !== null),
                'after:published_at',
            ],
        ];
    }

    public function messages(){
        return [
            'order.unique' => __("order.unique")
        ];
    }
}
