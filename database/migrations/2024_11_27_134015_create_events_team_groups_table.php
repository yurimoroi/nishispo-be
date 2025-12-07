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
        Schema::create('events_team_groups', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('event_id')->references('id')->on('events')->nullable(false);
            $table->foreignUlid('team_group_id')->references('id')->on('team_groups')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events_team_groups');
    }
};
