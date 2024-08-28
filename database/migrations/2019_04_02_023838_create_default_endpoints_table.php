<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_endpoints', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gateway_id');
            $table->unsignedInteger('telecom_id')->nullable();
            $table->smallInteger('sort_col')->default(0);
            $table->boolean('active_endpoint')->default(0);
            $table->boolean('inactive')->default(0);
            $table->foreign('gateway_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('telecom_id')->references('id')->on('operators')->onDelete('set null');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_endpoints');
    }
}