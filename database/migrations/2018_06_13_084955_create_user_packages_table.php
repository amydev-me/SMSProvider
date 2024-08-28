<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('package_id')->nullable();
            $table->unsignedInteger('promotion_id')->nullable();
            $table->string('invoice_no',50)->nullable();

            $table->double('cost')->default(0);
            $table->integer('credit')->nullable();
            $table->integer('total_sms')->nullable();
            $table->double('total_usd')->nullable();
            $table->double('extra_credit')->nullable();
            $table->double('total_credit')->default(0);

            $table->string('payment_method');
            $table->datetime('payment_date')->nullable();
            $table->dateTime('order_date');
            $table->string('status');
            
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_packages');
    }
}
