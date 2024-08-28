<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_package_id');
            $table->unsignedInteger('package_id');
            $table->text('message');
            $table->tinyInteger('read')->default('0');
            $table->datetime('read_at')->nullable();
            $table->timestamps();
            $table->foreign('user_package_id')->references('id')->on('user_packages')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_notifications');
    }
}
