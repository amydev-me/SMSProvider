<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('schedule_message_id');
            $table->string('recipient', 50);
            $table->string('country', 50)->nullable();
            $table->string('operator', 50)->nullable();
            $table->string('source', 25)->nullable();
            $table->integer('total_usage')->default(0);
            $table->timestamps();
            $table->foreign('schedule_message_id')->references('id')->on('schedule_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_details');
    }
}
