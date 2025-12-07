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
        Schema::create('organizations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->nullable(false)->reference('id')->on('companies');
            $table->string('name', 100)->nullable(false);
            $table->string('representative_name', 100)->nullable(false);
            $table->string('tel_number', 30)->nullable(false);
            $table->string('email', 255)->nullable(false);
            $table->text('activity_description')->nullable(false);
            $table->integer('birthyear_viewing_flg')->default(0)->nullable();
            $table->integer('birthdate_viewing_flg')->default(0)->nullable();
            $table->integer('postal_code_viewing_flg')->default(0)->nullable()->comment('0: Event leader or above, 1: All users');
            $table->integer('address_viewing_flg')->default(0)->nullable()->comment('0: Event leader or above, 1: All users');
            $table->integer('phone_number_viewing_flg')->default(0)->nullable()->comment('0: Event leader or above, 1: All users');
            $table->integer('mobile_phone_number_viewing_flg')->default(0)->nullable()->comment('0: Event leader or above, 1: All users');
            $table->integer('email_viewing_flg')->default(0)->nullable()->comment('0: Event leader or above, 1: All users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('organizations_users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->nullable(false)->references('id')->on('organizations')->onDelete('cascade');
            $table->foreignUlid('user_id')->nullable(false)->references('id')->on('users')->onDelete('cascade');
            $table->integer('status')->default(0)->nullable()->comment('0: Applying for membership, 1: Affiliation, 2: Applying for withdrawal, 3: Withdrawal');
            $table->integer('administrator_flg')->default(0)->comment('0: General member, 1: Organization administrator');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations_users');
        Schema::dropIfExists('organizations');
    }
};
