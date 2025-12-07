<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Modules\Event\Models\EventCategory;
use App\Modules\Event\Models\LocationCategory;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            OrganizationSeeder::class,
            TagSeeder::class,
            ArticleCategorySeeder::class,
            ArticleSeeder::class,
            TopArticleSeeder::class,
            AlignmentMediaSeeder::class,
            ArticleTagSeeder::class,
            ContributorTrainingsSeeder::class,
            TeamSeeder::class,
            EventCategorySeeder::class,
            LocationCategorySeeder::class,
            LocationSeeder::class,
            TeamGroupSeeder::class,
            EventSeeder::class,
            InquiryTypeSeeder::class,
            InquirySeeder::class,
            InformationSeeder::class
        ]);
    }
}
