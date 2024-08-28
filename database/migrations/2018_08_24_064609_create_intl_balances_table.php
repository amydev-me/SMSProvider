<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntlBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intl_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('intl_purchase_id');
            $table->double('balance');
            $table->timestamps();
            $table->foreign('intl_purchase_id')->references('id')->on('intl_purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intl_balances');
    }
}
