<?php

namespace Database\Seeders;

use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $article = Article::all();

        $article->each(function ($article, $key) {
            $article->tags()->attach(ArticleTag::inRandomOrder()->first(), ["id" => Str::ulid()]);
            $article->tags()->attach(ArticleTag::inRandomOrder()->first(), ["id" => Str::ulid()]);
            $article->tags()->attach(ArticleTag::inRandomOrder()->first(), ["id" => Str::ulid()]);
            $article->tags()->attach(ArticleTag::inRandomOrder()->first(), ["id" => Str::ulid()]);
            $article->tags()->attach(ArticleTag::inRandomOrder()->first(), ["id" => Str::ulid()]);
        });
    }
}
