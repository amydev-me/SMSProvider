<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('telecom_id')->nullable();
            $table->string('message_id');
            $table->string('status', 255);
            $table->string('destination', 255);
            $table->string('sender', 255);
            $table->string('operator_date', 255);
            $table->timestamps();
            $table->foreign('telecom_id')->references('id')->on('telecoms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator_logs');
    }
}
