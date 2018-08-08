<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_members', function (Blueprint $table) {
            $table->string('id');
            $table->string('list_id');
            $table->string('email_address');
            $table->string('unique_email_id')->unique();
            $table->string('email_type');
            $table->string('status');
            $table->string('unsubscribe_reason');
            $table->text('merge_fields');
            $table->text('interests');
            $table->text('stats');
            $table->string('ip_signup');
            $table->string('timestamp_signup');
            $table->string('ip_opt');
            $table->dateTime('timestamp_opt');
            $table->string('member_rating');
            $table->dateTime('last_changed');
            $table->string('language');
            $table->boolean('vip');
            $table->string('email_client');
            $table->text('location');
            $table->text('marketing_permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_members');
    }
}
