<?php

namespace App\Http\Requests;

use App\Enums\ContributorTrainingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class ContributorTrainingsRequest extends FormRequest
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
            'type' => new EnumRule(ContributorTrainingTypeEnum::class), // Type can be 0 (video) or 1 (blog)
            'title' => 'required|string|max:100', // Title is required with max 100 characters
            'url' => 'required|url|max:255', // URL should be a valid URL with max length 255
            'no' => 'nullable|integer|min:0', // No can be null, otherwise an integer >= 0
            'overview' => 'nullable|string|max:300', // Overview can be null or string with max 300 characters
            'deleted_at' => 'nullable|date_format:Y-m-d H:i:s', // Deleted_at can be null or a valid timestamp format
        ];
    }

    public function messages()
    {
        return [
            'type.in' => 'The type must be either 0 for video or 1 for blog.',
            'url.url' => 'The URL must be a valid URL.',
            'url.max' => 'The URL must not exceed 255 characters.',
            'title.required' => 'The title is required.',
            'title.max' => 'The title must not exceed 100 characters.',
            'overview.max' => 'The overview must not exceed 300 characters.',
            'deleted_at.date_format' => 'The deleted_at field must be a valid timestamp.',
        ];
    }
}
