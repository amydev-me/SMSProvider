<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 100);
            $table->string('email', 50);
            $table->string('mobile', 50);
            $table->string('password');
            $table->string('full_name', 255)->nullable();
            $table->string('company', 255)->nullable();
            $table->string('account_type', 50)->nullable();
            $table->boolean('verified')->default(0);
            $table->mediumText('address')->nullable();
            $table->boolean('accept_terms')->default(0);

            // $table->tinyInteger('newsletter')->default('1');
            $table->string('sms_type')->default('Package');
            $table->double('usd_rate')->default(0);

            // $table->integer('minimum_credit')->default('0');
            $table->tinyInteger('block')->default('0');
            $table->tinyInteger('obsolete')->default('0');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}