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
        Schema::create('teams_users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->nullable(false)->references('id')->on('teams');
            $table->foreignUlid('user_id')->nullable(false)->references('id')->on('users');
            $table->integer('leader_flg')->default(0)->nullable()->comment('0: General Member, 1: Event Leader');
            $table->integer('status')->default(0)->nullable()->comment(
                'Status codes: 
                0: Pending approval, 
                1: Approved (billing not set), 
                2: Approved (billing set), 
                3: Event participation canceled, 
                4: Affiliated (approved), 
                5: Withdrawal request pending, 
                6: Withdrawal request (billing not released), 
                7: Upgrade to regular member pending, 
                8: Upgrade approved (billing not set), 
                9: Upgrade approved (billing set), 
                10: Downgrade request pending, 
                11: Downgrade approved (billing not released). 
                Member type (viewing/regular) defined by `member_type`.'
            );
            $table->foreignUlid('team_invite_token_id')->nullable()->references('id')->on('team_invite_tokens')->comment('Associate the token with the user to indicate that you have registered by invitation If null, request or administrator registration')->nullable();
            $table->integer('member_type')->default(0)->comment('0: Viewing Member, 1: Regular Member, 2: Event Payment Membership
                What can be done with groupware is different
                If you are a member of the collection category at once, you can use it as a regular member as long as the right to use it exists.
                If the right does not exist, it will be treated as a viewing member.')->nullable();
            $table->integer('current_team_flg')->default(1)->nullable()->comment('0: Not in use, 1: in use
                In the case of a user who belongs to more than one event, one event is always one for each organization.
                If you do not belong to more than one event, it will be 1.');
            $table->integer('purchase_status')->default(0)->nullable()->comment('0: Not configured, 1: Configured');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams_users');
    }
};
