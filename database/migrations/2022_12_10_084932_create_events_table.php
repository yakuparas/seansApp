<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->integer('service_id');
            $table->integer('event_type_id');
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->integer('payment_id')->nullable();
            $table->string('event_name');
            $table->timestamp('event_start');
            $table->timestamp('event_end');
            $table->tinyInteger('isOccured');
            $table->tinyInteger('isCancelled');
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
        Schema::dropIfExists('events');
    }
};
