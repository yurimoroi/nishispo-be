<?php

namespace Database\Factories;

use App\Modules\Articles\Models\ArticleTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleTagFactory extends Factory
{
    protected $model = ArticleTag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     
    public function definition(): array
    {
        return [
            "name" => "#".fake()->word(),
        ];
    }
}
