<?php

namespace Database\Seeders;

use App\Modules\Event\Models\Location;
use App\Modules\Event\Models\LocationCategory;
use App\Modules\Event\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the first team and location category to associate
        $team = Team::first(); // Fetching the first available team
        $locationCategory = LocationCategory::first(); // Fetching the first available location category

        // Check if the team and location category exist, if not, exit the seeding process
        if (!$team || !$locationCategory) {
            $this->command->info("No teams or location categories found. Please create them before seeding locations.");
            return;
        }

        // Create locations for the given team
        Location::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key linking to the teams table
            'location_category_id' => $locationCategory->id, // Foreign key linking to the location categories table
            'name' => 'Main Training Facility',
            'address' => '1234 Training St, Example City, EX 12345',
            'description' => 'The main facility for team training sessions and events.',
            'contact' => '123-456-7890',
            'map_url' => 'https://maps.example.com/location1',
            'google_map_flg' => 1,
            'latitude' => 40.712776,
            'longitude' => -74.005974,
        ]);

        Location::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key linking to the teams table
            'location_category_id' => $locationCategory->id, // Foreign key linking to the location categories table
            'name' => 'Secondary Training Location',
            'address' => '5678 Secondary St, Example City, EX 67890',
            'description' => 'An additional location for practice and smaller events.',
            'contact' => '098-765-4321',
            'map_url' => 'https://maps.example.com/location2',
            'google_map_flg' => 0,
            'latitude' => 40.730610,
            'longitude' => -73.935242,
        ]);
    }
}
