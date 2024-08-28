<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('admin_id')->nullable();
            $table->string('sender_name', 50)->nullable();
            $table->string('batch_id', 50);
            //Unicode,Plain text
            $table->mediumText('message_content');
            $table->integer('message_parts')->default(0);
            $table->string('encoding', 50);
            $table->integer('total_credit')->default(0);
            $table->integer('total_sms')->default(0);
            $table->integer('total_characters')->default(0);
            $table->string('source', 50)->nullable();
            $table->string('type', 50);
            $table->string('sms_type')->default('Package');
            $table->timestamp('create_sms')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
}
