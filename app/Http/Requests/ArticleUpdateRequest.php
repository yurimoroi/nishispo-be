<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleUpdateRequest extends FormRequest
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
            "title" => 'required|string|max:200',
            "body" => 'required|string',
            "organization_id" => 'required',
            "published_at" => 'required|date|date_format:Y-m-d H:i:s',
            'publish_ended_at' => [
                'nullable',
                'date',
                'date_format:Y-m-d H:i:s',
                Rule::requiredIf(fn() => request('publish_ended_at') !== null),
                'after:published_at',
            ],
            'attachments[]' => 'nullable|file|mimes:jpg,png|max:2048',
            "categories[]" => "nullable",
            "categories.*" => "string",
            "tags" => "nullable",
            "tags.*" => "string"
        ];
    }

    public function messages()
    {
        return [
            "title.required" => __("article_title.required"),
            "body.required" => __("article_body.required"),
            "organization_id.required" => __("article_organization.requried"),
            "published_at.required" => __("article_publication.required")
        ];
    }
}
