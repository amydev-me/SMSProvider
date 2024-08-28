<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerifyUserEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verify_user_emails', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->string('token')->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->dateTime('resend_at')->nullable();
            $table->integer('resend_count')->default(0);
            $table->integer('resent_in')->detault(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verify_user_emails');
    }
}
