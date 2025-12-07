<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlignmentMediaRequest extends FormRequest
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
            "name" => "required|max:100",
            "url" => 'required|url|max:255',
            "order" => 'integer',
            "display_top_flg" => 'boolean',
            "display_article_list_flg" => 'boolean',
            "display_flg" => 'boolean',
            "note" => 'string', 
            "started_at" => 'required|date|date_format:Y-m-d H:i:s',
            "ended_at" => 'nullable|date|date_format:Y-m-d H:i:s|after:started_at',
        ];
    }
}
