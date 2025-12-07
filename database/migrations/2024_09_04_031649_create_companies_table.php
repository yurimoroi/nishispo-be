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
        Schema::create('companies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name', 100)->nullable(false);
            $table->string('tel_number', 30)->nullable();
            $table->string('leader_name', 100)->nullable(false);
            $table->string('email', 255)->nullable();
            $table->text('terms')->nullable();
            $table->dateTime('terms_updated_at')->nullable();
            $table->text('about_service')->nullable();
            $table->dateTime('about_service_updated_at')->nullable();
            $table->text('about_company')->nullable();
            $table->dateTime('about_company_updated_at')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->dateTime('privacy_policy_updated_at')->nullable();
            $table->text('about_report')->nullable();
            $table->dateTime('about_report_updated_at')->nullable();
            $table->text('about_publish_content')->nullable();
            $table->dateTime('about_publish_content_updated_at')->nullable();
            $table->text('ad')->nullable();
            $table->dateTime('ad_updated_at')->nullable();
            $table->text('reporter_editor')->nullable();
            $table->dateTime('reporter_editor_updated_at')->nullable();
            $table->integer('post_limit')->default(2)->nullable()->comment("Daily posting limit for general article contributors");
            $table->integer('organization_member_post_limit')->default(3)->nullable()->comment("Daily posting limit for general article contributors who are also organization members");
            $table->integer('organization_post_limit')->default(10)->nullable()->comment("Maximum total number of daily posts for each organization for general article contributors who are also organization members");
            $table->string('line_official_ch_url', 255);
            $table->dateTime('trial_campaigh_started_at')->nullable();
            $table->dateTime('trial_campaigh_ended_at')->nullable();
            $table->integer('trial_days')->default(0)->nullable();
            $table->integer('trial_enabled_flg')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
