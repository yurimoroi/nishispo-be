<?php

namespace Database\Seeders;

use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\TopArticle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::published()->whereHas('media')->limit(10)->get();

        if( $articles){
            $articles->each(function ($article, $key) {
                TopArticle::create([
                    'article_id' => $article->id,
                    'order' => $key + 1,
                    'published_at' => now(),
                    'publish_ended_at' => now()->addDays(7),
                ]);
            });
        }
       
    }
}
