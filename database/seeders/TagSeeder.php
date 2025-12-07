<?php

namespace Database\Seeders;

use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ArticleTag::factory()
            ->hasAttached(Article::factory()->count(5), function () {
                return ['id' => Str::ulid()]; // Generate a unique ULID for each article
            }, 'articles')
            ->count(20)
            ->create();
    }
}
