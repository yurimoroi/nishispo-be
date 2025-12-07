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
        Schema::create('top_articles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->onDelete('cascade');
            $table->integer('order')->nullable(false);
            $table->dateTime('published_at')->nullable();
            $table->dateTime('publish_ended_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['article_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_articles');
    }
};
