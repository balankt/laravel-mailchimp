<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_lists', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('web_id');
            $table->string('name');
            $table->text('contact');
            $table->string('permission_reminder');
            $table->boolean('use_archive_bar');
            $table->text('campaign_defaults');
            $table->string('notify_on_subscribe');
            $table->string('notify_on_unsubscribe');
            $table->dateTime('date_created');
            $table->integer('list_rating');
            $table->boolean('email_type_option');
            $table->string('subscribe_url_short');
            $table->string('subscribe_url_long');
            $table->string('beamer_address');
            $table->string('visibility');
            $table->boolean('double_optin');
            $table->boolean('marketing_permissions');
            $table->text('stats');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_lists');
    }
}
