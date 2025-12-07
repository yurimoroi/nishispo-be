<?php

namespace App\Http\Requests;

use App\Modules\Company\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'family_name' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'phonetic_family_name' => 'required|string|max:100',
            'phonetic_given_name' => 'required|string|max:100',
            'nickname' => 'nullable|string|max:100',
            'birth_date' => 'required|date|date_format:Y-m-d',
            'gender_type' => 'nullable|integer',
            'postal_code' => 'nullable|string|max:10',
            'province' => 'nullable|integer',
            'address_1' => 'nullable|string|max:200',
            'address_2' => 'nullable|string|max:200',
            'address_3' => 'nullable|string|max:200',
            'phone_number' => 'nullable|string|max:30',
            'mobile_phone_number' => 'nullable|string|max:30',
            'login_id' => [
                'nullable',
                'string',
                'max:100', 
                Rule::unique('users','login_id')->withoutTrashed()
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users','email')->withoutTrashed(),
            ],
            'password' =>  ['required', Password::min(8)->mixedCase(), 'confirmed'],
            'contributor_name' => 'nullable|string|max:100|required_if:contributor_status,1',
            'rakuten_id' => 'nullable|string|max:255',
            'favorite_sport' => 'nullable|string|max:255',
            'favorite_gourmet' => 'nullable|string|max:255',
            'secretariat_flg' => 'nullable|boolean',
            'contributor_status' => 'nullable|integer',
            'advertiser_flg' => 'nullable|boolean',
            'advertiser_name' => 'nullable|string|max:200',
            'line_id' => 'nullable|string|max:255',
            'x_id' => 'nullable|string|max:255',
            'facebook_id' => 'nullable|string|max:255',
            'instagram_id' => 'nullable|string|max:255',

             
            'affiliate_id' => ['nullable' , 'array' , function ($attribute, $value, $fail) {
                $exists = Organization::whereIn('id', $value)->pluck('id')->toArray();
                $missing = array_diff($value, $exists);

                if (!empty($missing)) {
                    $fail("Organization IDs is invalid: " . implode(', ', $missing));
                }
            },],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
        ];
    }

    public function messages()
    {
        return [
            "family_name.required" => __("family_name.required"),
            "given.required" => __("given.required"),
            "phonetic_family_name.required" => __("phonetic_family_name.required"),
            "phonetic_given_name.required" => __("phonetic_given_name.required"),
            "date_of_birth.required" => __("date_of_birth.required"),
            "postal_code.required" => __("postal_code.required"),
            "postal_code.max" => __("postal_code.max"),
            "province.required" => __("province.required"),
            "address_1.required" => __("address_1.required"),
            "address_2.required" => __("address_2.required"),
            "address_3.required" => __("address_3.required"),
            "phone_number.max" => __("phone_number.max"),
            "mobile_phone_number.max" => __("mobile_phone_number.max"),
            "email.required" => __("email.required"),
            "email.unique" => __("email.unique"),
            "password.required" => __("password.required"),
            "password.min" => __("password.min"),
            "password.mixed_case" => __("password.mixed_case"),
            "password.numbers" => __("password.numbers"),
            "password.symbols" => __("password.symbols"),
            "password.uncompromised" => __("password.uncompromised"),
            "password.confirmed" => __("password.confirmed"),
            "password_confirmation.required" => __("password_confirmation.required"),
            "nickname.max" => __("nickname.max"),
            "favorite_sport.max" => __("favorite_sport.max"),
            "favorite_gourmet.max" => __("favorite_gourmet.max"),
            "contributor_name.required_if" => __("contributor_name.required_if"),
            "login_id.unique" => __("login_id.unique"),
            "advertiser_name.required_if" => __("advertiser_name.required_if"),
            "avatar.image" => __("avatar.image"),
            "avatar.mimes" => __("avatar.mimes"),
            "avatar.max" => __("avatar.max")
        ];
    }
}
