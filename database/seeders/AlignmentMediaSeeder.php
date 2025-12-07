<?php

namespace Database\Seeders;

use App\Modules\Company\Models\AlignmentMedia;
use App\Modules\Company\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlignmentMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        AlignmentMedia::create([
            'company_id' => $company->id,
            'name' => 'Sample Name',
            'url' => 'https://picsum.photos/id/160/3200/2119',
            'order' => 1,
            'display_top_flg' => true,
            'display_article_list_flg' => true,
            'display_flg' => true,
            'started_at' => now(),
            'ended_at' =>  now()->addYear(1),
        ]);
       
    }
}
