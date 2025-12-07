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
        Schema::create('article_tag_ranks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('article_tag_id')->nullable(false)->references('id')->on('article_tags')->onDelete('cascade');
            $table->integer('count')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tag_ranks');
    }
};
