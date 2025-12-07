<?php

namespace Database\Factories;

use App\Modules\Company\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
            'representative_name' => fake()->firstName(),
            'tel_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'activity_description' => fake()->sentence(),
            'postal_code_viewing_flg' => fake()->boolean(),
            'address_viewing_flg' => fake()->boolean(),
            'phone_number_viewing_flg' => fake()->boolean(),
            'mobile_phone_number_viewing_flg' => fake()->boolean(),
            'email_viewing_flg' => fake()->boolean(),
        ];
    }
}
