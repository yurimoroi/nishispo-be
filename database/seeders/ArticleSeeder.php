<?php

namespace Database\Seeders;



use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            "https://picsum.photos/id/160/3200/2119",
            "https://picsum.photos/id/161/3200/2119",
            "https://picsum.photos/id/162/3200/2119",
            "https://picsum.photos/id/163/3200/2119",
            "https://picsum.photos/id/164/3200/2119",
            "https://picsum.photos/id/165/3200/2119",
            "https://picsum.photos/id/166/3200/2119",
            "https://picsum.photos/id/167/3200/2119",
            "https://picsum.photos/id/179/3200/2119",
            "https://picsum.photos/id/169/3200/2119",
            "https://picsum.photos/id/170/3200/2119"
        ];

        $article = Article::factory()
            ->count(100)
            ->create();

        $article->each(function ($article, $key) use ($images) {
            $article->categories()->attach(ArticleCategory::inRandomOrder()->first(), ["id" => Str::ulid()]);
            if ($key % 2 === 0) {
                try {
                    $article->addMediaFromUrl($images[array_rand($images)])->toMediaCollection('images');
                    $article->addMediaFromUrl($images[array_rand($images)])->toMediaCollection('images');
                    $article->addMediaFromUrl($images[array_rand($images)])->toMediaCollection('images');
                } catch (\Exception $e) {
                    // 画像のダウンロードに失敗した場合はスキップ
                    $this->command->warn("Failed to download image for article {$article->id}: " . $e->getMessage());
                }
            }
        });
    }
}
