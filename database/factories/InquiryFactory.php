<?php

namespace Database\Factories;

use App\Modules\Admin\Inquiry\Models\Inquiry;
use App\Modules\Admin\Inquiry\Models\InquiryType;
use App\Modules\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Inquiry\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    const inquiries = [
        "About article content",
        "Regarding recruitment of reporters and editorial staff",
        "About advertising in the media",
        "About sponsorship and support",
        "About services and functions",
        "About supporters",
        "Applications and inquiries for groupware functions",
        "Inquiries to the operating company",
        "Other inquiries"
    ];

    public function definition(): array
    {
        $company = Company::first();

        return [
            "inquiry_type_id" => InquiryType::inRandomOrder()->pluck('id')->first() ,
            "name" => fake()->firstName() ,
            "body" => fake()->paragraph(),
            "email" => fake()->unique()->safeEmail(),
        ];
    }
}
