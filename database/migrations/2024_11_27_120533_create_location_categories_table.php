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
        Schema::create('location_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->string('name', 200)->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->foreignUlid('location_category_id')->references('id')->on('location_categories')->nullable(false);
            $table->string('address', 200)->nullable();
            $table->text('description')->nullable();
            $table->string('contact', 100)->nullable()->comment('At Circle Square, you can enter not only phone numbers but also characters freely.');
            $table->string('map_url', 255)->nullable()->comment('Assuming that you will include the URL of the website of the map or facility');
            $table->integer('google_map_flg')->nullable()->default(0)->comment('0: Do not use, 1: Use');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('location_categories');
    }
};
