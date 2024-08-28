<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaygInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payg_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('invoice_no',50)->nullable();

            $table->double('cost')->default(0);
            $table->integer('credit')->nullable();
            $table->double('total_credit')->default(0);

            $table->string('payment_method');
            $table->datetime('payment_date')->nullable();
            $table->dateTime('invoice_date');
            $table->string('status');

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payg_invoices');
    }
}
