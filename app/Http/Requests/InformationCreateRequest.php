<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InformationCreateRequest extends FormRequest
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
            'title' => 'required|string|max:200',
            'body' => 'required|string',

            'published_at' => [
                'required',
                'date',
                request()->filled('finished_at') ? 'before_or_equal:finished_at' : null,
            ],

            'finished_at' => [
                'nullable',
                'date',
                Rule::requiredIf(fn() => request('finished_at') !== null),
                'after:published_at',
            ],

            'info_images' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('info_title.required'),
            'publised_at.before_or_equal' => __('info_published_at.before'),
            'body.required' => __('info_body.required')
        ];
    }
}
