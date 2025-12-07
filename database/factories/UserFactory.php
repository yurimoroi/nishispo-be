<?php

namespace Database\Factories;

use App\Modules\Company\Models\Company;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = Company::first();

        $advister_flg = random_int(0, 1);
        $contributor_status = random_int(0, 3);
        $address = fake()->address();
        $gender = random_int(0, 2);
        $f_name =  fake()->lastName();
        $g_name = $gender == 1  || $gender == 0 ? fake()->firstNameFemale() : fake()->firstNameMale();

        return [
            'company_id' => $company ? $company->id : 1,
            'family_name' => $f_name,
            'given_name' =>  $g_name,
            'phonetic_family_name' => $f_name,
            'phonetic_given_name' =>  $g_name,
            'nickname' => $f_name . " ".  $g_name,
            'gender_type' =>  $gender,
            'postal_code' => fake()->postcode(),
            // 'province' =>
            'address_1' =>  $address,
            'address_2' =>  $address,
            'address_3' =>  $address,
            'phone_number' => fake()->phoneNumber(),
            'mobile_phone_number' => fake()->phoneNumber(),
            'login_id' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'birth_date' => Carbon::createFromDate(1995, 5, 9),
            'contributor_status' => $contributor_status,
            'contributor_name' => $contributor_status === 3 ? fake()->name() : null,
            'advertiser_flg' => $advister_flg,
            'advertiser_name' => $advister_flg ? fake()->name() : null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
