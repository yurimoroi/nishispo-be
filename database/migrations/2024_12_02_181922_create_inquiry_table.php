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
        Schema::create('inquiry_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->nullable(false)->reference('id')->on('companies');
            $table->string('name', 100)->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->nullable(false)->reference('id')->on('companies');
            $table->foreignUlid('inquiry_type_id')->nullable(false)->reference('id')->on('inquiry_types');
            $table->string('name', 100)->nullable(false);
            $table->text('body')->nullable(false);
            $table->string('email', 255)->nullable(false);
            $table->text('reply')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_types');
        Schema::dropIfExists('inquiries');
    }
};
