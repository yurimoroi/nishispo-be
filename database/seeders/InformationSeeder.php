<?php

namespace Database\Seeders;

use App\Modules\Admin\Information\Models\Informations;
use App\Modules\Company\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Informations::create([
            'title' => "Information 1",
            'body' => fake()->paragraph(10),
            'published_at' => now(),
            'finished_at' => now()->addMonths(2)
        ]);

        Informations::create([
            'title' => "Information 2",
            'body' => fake()->paragraph(10),
            'published_at' => now(),
            'finished_at' => now()->addMonths(2)
        ]);

        Informations::create([
            'title' => "Information 3",
            'body' => fake()->paragraph(10),
            'published_at' => now(),
            'finished_at' => now()->addMonths(2)
        ]);
    }
}
