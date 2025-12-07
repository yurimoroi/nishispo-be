<?php

namespace Database\Seeders;

use App\Modules\Event\Models\Team;
use App\Modules\Event\Models\TeamGroups;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = Team::first(); // Assuming a team exists

        // Create team groups
        TeamGroups::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key to the 'teams' table
            'name' => 'Team Group A',
            'description' => 'Description for Team Group A.',
        ]);

        TeamGroups::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key to the 'teams' table
            'name' => 'Team Group B',
            'description' => 'Description for Team Group B.',
        ]);

        TeamGroups::create([
            'id' => Str::ulid(),
            'team_id' => $team->id, // Foreign key to the 'teams' table
            'name' => 'Team Group C',
            'description' => 'Description for Team Group C.',
        ]);
    }
}
