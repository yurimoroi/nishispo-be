<?php

namespace Database\Seeders;

use App\Modules\Articles\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ArticleCategory::create([
            "name" => "SC21",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 1,
            "special_flg" => 1
        ]);

        ArticleCategory::create([
            "name" => "FC Nishinomiya",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 2,
            "special_flg" => 1
        ]);

        ArticleCategory::create([
            "name" => "Category 3",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 3,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 4",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 4,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 5",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 5,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 6",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 6,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 7",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 7,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 8",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 8,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 9",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 9,
            "special_flg" => 0
        ]);
        ArticleCategory::create([
            "name" => "Category 10",
            "color" => "#FFFFFF",
            "show_head_flg" => 1,
            "order" => 10,
            "special_flg" => 0
        ]);
    }
}
