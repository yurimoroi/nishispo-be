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
        Schema::create('event_reply_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('event_id')->references('id')->on('events')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_reply_requests');
    }
};
