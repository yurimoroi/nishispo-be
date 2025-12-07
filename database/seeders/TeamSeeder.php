<?php

namespace Database\Seeders;

use App\Modules\Company\Models\Organization;
use App\Modules\Event\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Fetch necessary foreign keys (Organization)
         $organization = Organization::first(); // Assuming an organization exists

         // Create teams
         Team::create([
             'id' => Str::ulid(),
             'organization_id' => $organization->id,
             'name' => 'Team A',
             'activity_description' => 'Description of Team A activities.',
             'member_information' => 'Information for Team A members.',
             'group_fee' => 1000, // example fee
             'collect_type' => 0, // Individual payment
             'collect_span' => 1, // Default value
             'closing_date' => 1, // Default value
             'first_estimated_number' => 20, // Example initial estimate
         ]);
 
         Team::create([
             'id' => Str::ulid(),
             'organization_id' => $organization->id,
             'name' => 'Team B',
             'activity_description' => 'Description of Team B activities.',
             'member_information' => 'Information for Team B members.',
             'group_fee' => 1500, // example fee
             'collect_type' => 1, // Event settlement
             'collect_span' => 2, // Example custom span
             'closing_date' => 0, // Custom closing date
             'first_estimated_number' => 30, // Example initial estimate
         ]);
 
         Team::create([
             'id' => Str::ulid(),
             'organization_id' => $organization->id,
             'name' => 'Team C',
             'activity_description' => 'Description of Team C activities.',
             'member_information' => 'Information for Team C members.',
             'group_fee' => null, // No fee
             'collect_type' => 0, // Individual payment
             'collect_span' => 1, // Default value
             'closing_date' => 1, // Default value
             'first_estimated_number' => 0, // No estimate
         ]);
    }
}
