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
        Schema::create('team_groups', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->string('name', 200)->nullable(false);
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('team_groups_users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_group_id')->references('id')->on('team_groups')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_groups_users');
        Schema::dropIfExists('team_groups');
    }
};
