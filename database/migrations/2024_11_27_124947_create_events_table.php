<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
            $table->foreignUlid('event_category_id')->nullable()->default(null)->references('id')->on('event_categories')->comment('null: No setting');
            $table->string('name', 200)->nullable(false);
            $table->text('description')->nullable();
            $table->datetime('started_at')->nullable(false);
            $table->datetime('ended_at')->nullable(false);
            $table->integer('all_day_flg')->nullable()->default(0);
            $table->integer('activity_location_type')->nullable(false)->comment('0: Free input, 1: Select from activity location');
            $table->foreignUlid('location_id')->nullable()->references('id')->on('locations');
            $table->string('location_name', 200)->nullable()->comment('Only used for free input');
            $table->integer('aggregation_location_flg')->nullable()->default(0);
            $table->integer('aggregation_location_type')->nullable()->comment('0: Free input, 1: Select from activity location');
            $table->foreignUlid('aggregation_location_id')->nullable()->references('id')->on('locations');
            $table->string('aggregation_location_name', 200)->nullable()->comment('Only used for free input');
            $table->foreignUlid('team_group_id')->nullable()->references('id')->on('team_groups');
            $table->integer('attendance_flg')->nullable()->default(0)->comment('Whether to take attendance or not');
            $table->integer('capacity')->nullable()->default(0);
            $table->datetime('reply_deadline')->nullable();
            $table->integer('not_other_answer_flg')->nullable()->default(0)->comment('0: Undecided/Other is displayed as an option, 1: Not displayed');
            $table->integer('late_declaration_flg')->nullable()->default(0)->comment('0: Not applicable, 1: Applicable');
            $table->integer('leave_early_declaration_flg')->nullable()->default(0)->comment('0: Not applicable, 1: Applicable');
            $table->integer('show_participant_list_type')->nullable()->default(0)->comment('0: Everyone can view, 1: Event creator and event leader or above can view');
            $table->integer('show_participant_classification_type')->nullable()->default(0)->comment('0: Everyone can view, 1: Event creator and event leader or above can view');
            $table->integer('save_timeline_flg')->nullable()->default(0)->comment('0: Not reflected, 1: Reflected');
            $table->integer('notification_setting')->nullable()->default(0)->comment('Depending on your settings, an email will be sent to participants before the event starts. 0: No setting, 1: 30 minutes before, 2: 1 hour before, 3: 2 hours before, 4: 3 hours before, 5: 6 hours before, 6: 12 hours before, 7: 24 hours before');
            $table->integer('repetition_flg')->nullable()->default(0)->comment('0: Normal, 1: Repeated event');
            $table->string('repetition_event_hash', 32)->nullable()->comment('Generated when registering a repeating event (event creator ULID + md5 value of creation date and time) Required for repeating events');
            $table->datetime('repetition_started_at')->nullable()->comment('Required for repeating events');
            $table->integer('repetition_ended_type')->nullable()->comment('0: No deadline (up to one year from the start date and time), 1: Deadline Required for repeating events');
            $table->datetime('repetition_ended_at')->nullable();
            $table->integer('repetition_interval_type')->nullable()->comment('0: Daily, 1: Weekly, 2: Monthly');
            $table->string('repetiton_week', 13)->comment('Only referenced when repetition_interval_type=1 Save from day to Saturday with 0,1 separated by commas Example: If every Wednesday and Friday: 0,0,0,1,0,1,0')->nullabe();
            $table->integer('repetition_month_basis_type')->nullable()->comment('0: Date, 1: Day of the week');
            $table->integer('repetition_month_day')->nullable()->comment('If repetition_month_basis_type=0, this is referenced. XX day of every month');
            $table->string('repetition_week_of_month', 9)->nullable()->comment('Referred when repetition_month_basis_type=1 Save from 1st to 5th as 0,1 separated by commas Example: 1st and 3rd: 1,0,1,0,0');
            $table->string('repetition_day_of_week', 13)->nullable()->comment('Referred when repetition_month_basis_type=1 Save from day to Saturday with 0,1 separated by commas Example: If every Wednesday and Friday: 0,0,0,1,0,1,0');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
