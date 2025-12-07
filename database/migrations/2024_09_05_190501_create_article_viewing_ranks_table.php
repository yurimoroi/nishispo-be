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
        Schema::create('article_viewing_ranks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->onDelete('cascade');
            $table->integer('view_count')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_viewing_ranks');
    }
};
