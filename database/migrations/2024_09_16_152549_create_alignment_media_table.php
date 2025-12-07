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
        Schema::create('alignment_media', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->nullable(false)->references('id')->on('companies')->onDelete('cascade');;
            $table->string('name' , 100)->nullable(false);
            $table->string('url' , 255)->nullable(false);;
            $table->integer('order')->default(0)->nullable();;
            $table->boolean('display_top_flg')->default(0)->nullable();
            $table->boolean('display_article_list_flg')->default(0)->nullable();
            $table->boolean('display_flg')->default(1)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('article_alignment_media', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('alignment_media_id')->nullable(false)->references('id')->on('alignment_media')->cascadeOnDelete();
            $table->foreignUlid('article_id')->nullable(false)->references('id')->on('articles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_alignment_media');
        Schema::dropIfExists('alignment_media');
    }
};
