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
        Schema::create('groupware_privileges', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->datetime('privilage_started_at')->nullable(false)->comment('Entitlement start date and time');
            $table->datetime('privilage_ended_at')->nullable(false)->comment('Entitlement start date and time');
            $table->string('nominal',200)->nullable(false);
            $table->integer('price')->nullable(false);
            $table->integer('payment_flg')->nullable(true)->comment('0: Unpaid, 1: Paid');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupware_privileges');
    }
};