<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntlPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intl_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount');
            $table->date('purchase_date');
            $table->tinyInteger('out_of_balance')->default('0');
            $table->tinyInteger('obsolete')->default('0');
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
        Schema::dropIfExists('intl_purchases');
    }
}
