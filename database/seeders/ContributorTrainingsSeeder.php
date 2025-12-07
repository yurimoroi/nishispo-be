<?php

namespace Database\Seeders;

use App\Modules\User\Models\ContributorTrainings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContributorTrainingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContributorTrainings::create([
            "type" => 1,
            "title" => "記事構成の基礎",
            "url" => "sample.com",
            "no" => 1,
            "overview" => 1
        ]);

        ContributorTrainings::create([
            "type" => 1,
            "title" => "写真撮影の基本",
            "url" => "sample.com",
            "no" => 2,
            "overview" => 1
        ]);

        ContributorTrainings::create([
            "type" => 1,
            "title" => "インタビュー技術",
            "url" => "sample.com",
            "no" => 3,
            "overview" => 1
        ]);

        ContributorTrainings::create([
            "type" => 1,
            "title" => "校正と編集",
            "url" => "sample.com",
            "no" => 4,
            "overview" => 1
        ]);

        ContributorTrainings::create([
            "type" => 1,
            "title" => "タイトル作成のコツ",
            "url" => "sample.com",
            "no" => 4,
            "overview" => 1
        ]);
    }

}
