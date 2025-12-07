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
        Schema::create('revised_articles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->onDelete('cascade');
            $table->foreignUlid('user_id')->nullable(false)->references('id')->on('users')->onDelete('cascade');
            $table->integer('request_type')->nullable()->comment("0: Temporary save, 1: Correction request, 2: Deletion request");
            $table->string('title', 200)->nullable();
            $table->text('body')->nullable();;
            $table->foreignUlid('organization_id')->nullable()->references('id')->on('organizations');
            $table->dateTime('published_at')->nullable();
            $table->dateTime('publish_ended_at')->nullable();
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
        Schema::dropIfExists('revised_articles');
    }
};
