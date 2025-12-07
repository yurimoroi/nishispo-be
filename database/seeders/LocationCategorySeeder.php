<?php

namespace Database\Seeders;

use App\Modules\Event\Models\LocationCategory;
use App\Modules\Event\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the first team to associate with the location categories
        $team = Team::first(); // Fetching the first available team

        // Check if a team exists, if not, exit the seeding process
        if (!$team) {
            $this->command->info("No teams found. Please create a team before seeding location categories.");
            return;
        }

        // Create some location categories for the given team
        LocationCategory::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key linking to the teams table
            'name' => 'Indoor',
        ]);

        LocationCategory::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key linking to the teams table
            'name' => 'Outdoor',
        ]);

        LocationCategory::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key linking to the teams table
            'name' => 'Virtual',
        ]);
    }
}
