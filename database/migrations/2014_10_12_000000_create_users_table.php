<?php

use App\Enums\UserType;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('device_token');
            $table->enum('type', UserType::getValues())->default(UserType::Employee);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('service_id')->nullable();
            $table->integer('company_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->json('country')->nullable();
            $table->json('city')->nullable();
            $table->json('district')->nullable();
            $table->string('adress')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('image')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
