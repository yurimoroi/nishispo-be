<?php

namespace App\Http\Requests;

use App\Enums\RevisedArticleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Http\Requests\TransformsEnums;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Illuminate\Validation\Rule;

class ArticleSaveRequest extends FormRequest
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
            "organization_id" => 'required|exists:organizations,id',
            "published_at" => 'required|date|date_format:Y-m-d H:i:s',
           'publish_ended_at' => [
                'nullable',
                'date',
                'date_format:Y-m-d H:i:s',
                Rule::requiredIf(fn() => request('publish_ended_at') !== null),
                'after:published_at',
            ],
            "comment" => 'nullable|string',
            "request_type" => ['nullable' , new EnumRule(RevisedArticleEnum::class)],
            'attachments[]' => 'nullable|file|mimes:jpg,png|max:2048',
            "categories[]" => "nullable",
            "categories.*" => "string",
            "tags" => "nullable",
            "tags.*" => "string",
            "alignment_medias" => 'nullable|array',
            "alignment_medias.*" => 'string|exists:alignment_media,id'
        ];
    }
}
