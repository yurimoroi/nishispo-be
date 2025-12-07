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
        Schema::create('article_tags', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->nullable(false)->references('id')->on('companies')->cascadeOnDelete();
            $table->string('name', 100)->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('articles_article_tags', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->cascadeOnDelete();;
            $table->foreignUlid('article_tag_id')->nullable(false)->references('id')->on('article_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['article_id', 'article_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles_article_tags');
        Schema::dropIfExists('article_tags');
    }
};
