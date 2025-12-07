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
        Schema::create('contributor_trainings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('type')->nullable()->comment('0: Video, 1: Blog');
            $table->string('title', 100)->nullable(false);
            $table->string('url', 255)->nullable(false)->comment('URL to external blog article or URL to video distribution page');
            $table->integer('no')->default(0)->nullable();
            $table->string('overview', 300)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('users_contributor_trainings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('contributor_training_id')->nullable(false)->references('id')->on('contributor_trainings')->onDelete('cascade');
            $table->foreignUlid('user_id')->nullable(false)->nullable(false)->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_contributor_trainings');
        Schema::dropIfExists('contributor_trainings');
    }
};
