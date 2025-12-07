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
        Schema::create('articles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->references('id')->on('companies');
            $table->foreignUlid('user_id')->nullable(false)->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 100)->nullable(false);
            $table->text('body')->nullable(false);
            $table->foreignUlid('organization_id')->nullable(false);
            $table->integer('pr_flg')->default(0)->nullable()->comment("If Articles created by advertiser users, this column will be 1 | true");
            $table->integer('status')->default(0)->nullable()->comment('0: Creating (private), 1: Applying, 2: Remand, 3: Applying (remand and revised), 4: Requesting editing, 5: Requesting deletion, 6: Publishing');
            $table->dateTime('published_at')->nullable();
            $table->dateTime('publish_ended_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['company_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
