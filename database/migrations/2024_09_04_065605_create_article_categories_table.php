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
        Schema::create('article_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('short_name', 100)->nullable();
            $table->string('color', 7)->nullable()->default('#000000')->comment('HEX color code');
            $table->integer('show_head_flg')->nullable()->default(0)->comment('Display in header?');
            $table->integer('order');
            $table->integer('special_flg')->nullable()->default(0)->comment('special category');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['company_id']);
        });

        Schema::create('articles_article_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->cascadeOnDelete();
            $table->foreignUlid('article_category_id')->nullable(false)->references('id')->on('article_categories')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['article_id', 'article_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles_article_categories');
        Schema::dropIfExists('article_categories');
    }
};
