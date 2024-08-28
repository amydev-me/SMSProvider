<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndpointToTelecomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telecoms', function (Blueprint $table) {
            $table->string('end_point')->nullable();
            $table->string('username')->nullable();
            $table->string('secret')->nullable();
            $table->boolean('inactive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telecoms', function (Blueprint $table) {
            //
        });
    }
}