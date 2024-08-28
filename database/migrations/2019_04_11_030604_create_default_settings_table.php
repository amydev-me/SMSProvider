<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender',50)->nullable();
            $table->string('facebook_url',255)->nullable();
            $table->string('twitter_url',100)->nullable();
            $table->string('linkedin_url',100)->nullable();
            $table->string('phones',255)->nullable();
            $table->string('email',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_settings');
    }
}
