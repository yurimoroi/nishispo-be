<?php

namespace Database\Seeders;

use App\Modules\Event\Models\Event;
use App\Modules\Event\Models\EventCategory;
use App\Modules\Event\Models\Location;
use App\Modules\Event\Models\LocationCategory;
use App\Modules\Event\Models\Team;
use App\Modules\Event\Models\TeamGroups;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $team = Team::first(); 
       $user = User::first(); 
       $eventCategory = EventCategory::first(); 
       $location = Location::first(); 
       $teamGroup = TeamGroups::first();

       // Create events
       Event::create([
           'id' => Str::ulid(),
           'team_id' => $team->id,
           'user_id' => $user->id,
           'event_category_id' => $eventCategory ? $eventCategory->id : null,
           'name' => 'Sample Event 1',
           'description' => 'This is a description for Sample Event 1.',
           'started_at' => Carbon::now()->addDays(1),
           'ended_at' => Carbon::now()->addDays(1)->addHours(2),
           'all_day_flg' => 0,
           'activity_location_type' => 1,
           'location_id' => $location->id,
           'location_name' => null,
           'aggregation_location_flg' => 0,
           'aggregation_location_type' => 1,
           'aggregation_location_id' => $location->id,
           'aggregation_location_name' => null,
           'team_group_id' => $teamGroup ? $teamGroup->id : null,
           'attendance_flg' => 1,
           'capacity' => 50,
           'reply_deadline' => Carbon::now()->addDays(1)->addHours(1),
           'not_other_answer_flg' => 0,
           'late_declaration_flg' => 0,
           'leave_early_declaration_flg' => 0,
           'show_participant_list_type' => 0,
           'show_participant_classification_type' => 0,
           'save_timeline_flg' => 0,
           'notification_setting' => 1,
           'repetition_flg' => 0,
           'repetition_event_hash' => null,
           'repetition_started_at' => null,
           'repetition_ended_type' => 0,
           'repetition_ended_at' => null,
           'repetition_interval_type' => 0,
           'repetiton_week' => 'week3',
           'repetition_month_basis_type' => null,
           'repetition_month_day' => null,
           'repetition_week_of_month' => null,
           'repetition_day_of_week' => null,
       ]);

       Event::create([
           'id' => Str::ulid(),
           'team_id' => $team->id,
           'user_id' => $user->id,
           'event_category_id' => $eventCategory ? $eventCategory->id : null,
           'name' => 'Sample Event 2',
           'description' => 'This is a description for Sample Event 2.',
           'started_at' => Carbon::now()->addDays(2),
           'ended_at' => Carbon::now()->addDays(2)->addHours(3),
           'all_day_flg' => 1,
           'activity_location_type' => 0,
           'location_id' => $location->id,
           'location_name' => 'Free input location for Sample Event 2',
           'aggregation_location_flg' => 1,
           'aggregation_location_type' => 1,
           'aggregation_location_id' => $location ? $location->id : null,
           'aggregation_location_name' => 'Aggregation location for Sample Event 2',
           'team_group_id' => $teamGroup ? $teamGroup->id : null,
           'attendance_flg' => 1,
           'capacity' => 100,
           'reply_deadline' => Carbon::now()->addDays(2)->addHours(1),
           'not_other_answer_flg' => 1,
           'late_declaration_flg' => 1,
           'leave_early_declaration_flg' => 0,
           'show_participant_list_type' => 1,
           'show_participant_classification_type' => 1,
           'save_timeline_flg' => 1,
           'notification_setting' => 2,
           'repetition_flg' => 1,
           'repetition_event_hash' => null,
           'repetition_started_at' => Carbon::now()->addDays(2),
           'repetition_ended_type' => 1,
           'repetition_ended_at' => Carbon::now()->addMonths(1),
           'repetition_interval_type' => 1,
           'repetiton_week' => '0,0,0,0,0,1,1',
           'repetition_month_basis_type' => 0,
           'repetition_month_day' => 15,
           'repetition_week_of_month' => null,
           'repetition_day_of_week' => null,
       ]);
    }
}
