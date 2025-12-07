<?php

namespace Database\Seeders;

use App\Modules\Event\Models\EventCategory;
use App\Modules\Event\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Fetch the first team to associate with the event categories
       $team = Team::first(); // Fetching the first available team

       // Check if a team exists, if not, exit the seeding process
       if (!$team) {
           $this->command->info("No teams found. Please create a team before seeding event categories.");
           return;
       }

       // Create event categories for the given team
       EventCategory::create([
           'id' => Str::ulid(),
           'team_id' => $team->id, // Foreign key linking to the teams table
           'name' => 'Practice',
           'color' => '#FF5733', // Example color code
       ]);

       EventCategory::create([
           'id' => Str::ulid(),
           'team_id' => $team->id, // Foreign key linking to the teams table
           'name' => 'Match',
           'color' => '#33FF57', // Example color code
       ]);

       EventCategory::create([
           'id' => Str::ulid(),
           'team_id' => $team->id, // Foreign key linking to the teams table
           'name' => 'Tournament',
           'color' => '#3357FF', // Example color code
       ]);
    }
}
