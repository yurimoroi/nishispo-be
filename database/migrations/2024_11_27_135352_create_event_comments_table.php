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
        Schema::create('event_comments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('event_id')->references('id')->on('events')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
            $table->string('comment', 500)->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_comments');
    }
};
