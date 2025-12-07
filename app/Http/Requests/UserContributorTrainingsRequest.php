<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserContributorTrainingsRequest extends FormRequest
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
            'contributor_training_id' => 'required|exists:contributor_trainings,id', 
        ];
    }

    /**
     * Get custom error messages for validator failures.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'contributor_training_id.required' => 'The contributor training ID is required.',
            'contributor_training_id.exists' => 'The selected contributor training does not exist.',
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
