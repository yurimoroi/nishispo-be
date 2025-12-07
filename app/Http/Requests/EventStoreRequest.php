<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
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
            'organization_id' => 'required|string|exists:organizations,id',
            'name' => 'required|string|max:255',
            'activity_description' => 'nullable|string',
            'member_information' => 'nullable|string',
            'group_fee' => 'nullable|numeric|min:0',
            'collect_type' => 'required|in:1,0',
            'collect_span' => 'required|integer|min:1',
            'closing_date' => 'nullable|in:1,0',
            'first_estimated_number' => 'nullable|integer|min:0',
            'event_images' => 'image|mimes:jpeg,png,jpg||max:512',
            'event_category_id' => 'nullable|string',
            'location_id' => 'nullable|string',
            'team_group_id' => 'nullable|string',
            'aggregation_location_id' => 'nullable|string',
            'event_leaders' => 'nullable|array',
            'event_leaders.*' => 'string|exists:users,id'
        ];
    }
}
