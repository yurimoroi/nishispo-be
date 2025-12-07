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
        Schema::create('weather_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->references('id')->on('companies');
            $table->string('area_code', 10)->nullable(false);
            $table->string('sub_area_code')->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_settings');
    }
};
