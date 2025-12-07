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
        Schema::create('event_replies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('event_id')->references('id')->on('events')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
            $table->integer('answer')->comment('1: Participate, 2: Not participate, 0: Undecided/Other')->nullable();
            $table->string('memo', 200)->nullable();
            $table->integer('late_declaration_flg')->nullable()->comment('0: No plans, 1: Plan to be late');
            $table->time('arrival_time')->nullable()->comment('Arrival time, referenced when late_declaration_flg=1');
            $table->integer('leave_early_declaration_flg')->comment('0: No plans, 1: Plan to leave early')->nullable();
            $table->time('leave_early_time')->comment('Referred when leave_early_declaration_flg=1')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_replies');
    }
};
