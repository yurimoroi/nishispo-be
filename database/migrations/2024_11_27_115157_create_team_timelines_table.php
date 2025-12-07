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
        Schema::create('team_timelines', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->references('id')->on('teams')->nullable(false);
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable(false);
            $table->integer('type')->nullable(false)->comment('0: Event creation, 1: Event editing, 2: Event attendance registration, 3: Activity location creation, 4: Activity location editing, 5: Survey creation, 6: Survey editing, 7: Survey response, 8: Discussion boarding, 9: Discussion board editing, 10: Thread creation, 11: Thread editing, 12: Less creation, 13: Less editing, 14: User addition to the event');
            $table->string('item_id', 26)->nullable(false)->comment('For target data acquisition, for linking when tapping');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_timelines');
    }
};
