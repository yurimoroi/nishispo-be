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
        Schema::create('rev_art_cat', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('revised_article_id')->nullable(false)->references('id')->on('revised_articles')->onDelete('cascade');
            $table->foreignUlid('article_category_id')->nullable(false)->references('id')->on('article_categories')->onDelete('cascade');
            $table->timestamps();

            $table->index(['revised_article_id', 'article_category_id']);
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rev_art_cat');
    }
};
