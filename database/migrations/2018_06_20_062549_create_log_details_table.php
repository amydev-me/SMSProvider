<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sms_log_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('operator_id')->nullable();
            $table->string('operator', 50)->nullable();
            $table->string('recipient', 50);
            $table->string('message_id', 50)->nullable();
            $table->string('status', 25)->nullable();
            $table->string('source', 25)->nullable();
            $table->integer('total_usage')->default(0);
            $table->timestamp('send_at')->nullable();
            $table->timestamps();
            $table->foreign('sms_log_id')->references('id')->on('sms_logs')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_details');
    }
}
