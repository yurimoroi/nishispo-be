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
        Schema::create('user_trial_privileges', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
            $table->datetime('trial_started_at')->nullable(false)->comment('Date and time of approval for participation in the event');
            $table->datetime('trial_ended_at')->nullable(false)->comment('trial_started_at date and time added to the number of trial days set by the secretariat (until 23:59:59)');
            $table->integer('trial_enable_flg')->default(1)->nullable()->comment('0: Disabled, 1: Enabled');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_trial_privileges');
    }
};