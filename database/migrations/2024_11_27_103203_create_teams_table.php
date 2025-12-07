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
        Schema::create('teams', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->references('id')->on('organizations');
            $table->string('name', 200)->nullable(false);
            $table->text('activity_description')->nullable()->comments('for display');
            $table->text('member_information')->nullable()->comments('for display');
            $table->integer('group_fee')->nullable();
            $table->integer('collect_type')->nullable()->comment('0: Individual payment, 1: Event settlement');
            $table->integer('collect_span')->nullable()->default(1);
            $table->integer('closing_date')->nullable()->default(1);
            $table->integer('first_estimated_number')->nullable()->default(0)->comment("Data for determining the initial charge amount It is mandatory in the case of event payment");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
