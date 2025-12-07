<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminArticleCreateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
            "categories" => "required|array",
            "categories.*" => "string",
            "tags" => "nullable",
            "tags.*" => "string",
            "alignment_medias" => "nullable|array",
            "alignment_medias.*" => "string",
            "publication_confirmation" => "accepted",
            "pr_flg" => "boolean",
            "is_published" => "nullable|boolean"
        ];
    }

    public function messages()
    {
        return [
            "title.required" => __("admin_article_title.required"),
            "body.required" => __("admin_article_body.required"),
            "organization_id.required" => __("admin_article_organization.required"),
            "published_at.required" => __("admin_article_publication.required"),
            "categories.required" => __("admin_article_categories.required"),
            "alignment_medias.required" => __("admin_article_alignment_medias.required"),
            "publication_confirmation.accepted" => __("admin_article_publication_article_flag.accepted"),
            "pr_flg.boolean" => __("admin_pr_flag_article.invalid")
        ];
    }
}
