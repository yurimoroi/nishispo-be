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
        Schema::create('remand_articles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->onDelete('cascade');
            $table->foreignUlid('user_id')->nullable(false)->references('id')->on('users')->onDelete('cascade');
            $table->text('comment_to_title')->nullable();
            $table->text('comment_to_body')->nullable();
            $table->text('comment_to_image')->nullable();
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remand_articles');
    }
};
