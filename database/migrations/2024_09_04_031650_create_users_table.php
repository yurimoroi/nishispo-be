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
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('company_id')->nullable(false)->references('id')->on('companies')->onDelete('cascade');
            $table->string('family_name', 100)->nullable(false);
            $table->string('given_name', 100)->nullable(false);
            $table->string('phonetic_family_name', 100)->nullable(false);
            $table->string('phonetic_given_name', 100)->nullable(false);
            $table->string('nickname', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('gender_type')->nullable()->default(0)->comment("0: No answer, 1: Female, 2: Male");
            $table->string('postal_code', 10)->nullable();
            $table->integer('province')->nullable();
            $table->string('address_1', 200)->nullable();
            $table->string('address_2', 200)->nullable();
            $table->string('address_3', 200)->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->string('mobile_phone_number', 30)->nullable();
            $table->string('login_id', 100)->nullable()->unique();
            $table->string('email', 255)->nullable(false);
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password', 255)->nullable(false);
            $table->string('password_reset_token', 255)->nullable();
            $table->string('contributor_name', 100)->nullable()->comment('Article poster name');
            $table->string('rakuten_id', 255)->nullable()->comment('Where to distribute article submission incentives');
            $table->string('favorite_sport', 255)->nullable();
            $table->string('favorite_gourmet', 255)->nullable();
            $table->integer('secretariat_flg')->default(0)->comment('Does it have secretariat authority?');
            $table->integer('contributor_status')->nullable()->default(0)->comment('0: Not applied, 1: Training in progress, 2: Training completed (waiting for approval), 3: Approved');
            $table->integer('advertiser_flg')->default(0)->comment('0: Regular account, 1: Advertiser account, have article contributor privileges. All articles posted will be PR');
            $table->string('advertiser_name', 200)->nullable()->comment('Advertiser name');
            $table->string('line_id', 255)->nullable();
            $table->string('x_id', 255)->nullable();
            $table->string('facebook_id', 255)->nullable();
            $table->string('instagram_id', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['company_id']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
