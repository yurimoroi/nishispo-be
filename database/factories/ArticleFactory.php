<?php

namespace Database\Factories;

use App\Enums\ArticleStatusEnum;
use App\Enums\UserContributorStatus;
use App\Modules\Articles\Models\Article;
use App\Modules\Company\Models\Organization;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Faker\FakerEnumProvider;
use Faker\Generator as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Article\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = new Faker();
        $faker->addProvider(new FakerEnumProvider($faker));
        $organization_id = Organization::where('company_id' ,1)->inRandomOrder()->pluck('id')->first();
        $user = User::where('contributor_status' , UserContributorStatus::approved()->value)->inRandomOrder()->first();
        $published_at = Carbon::now()->toImmutable();

        return [
            'user_id' =>  $user->id,
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'organization_id' => $organization_id,
            'pr_flg' =>  $user->isAdvertiser(),
            'status' => $faker->randomEnum(ArticleStatusEnum::class),
            'published_at' =>  $published_at,
            'publish_ended_at' =>  $published_at->addDays(random_int(1, 30)),
        ];
    }
}
