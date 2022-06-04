<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserShippingAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shipping_address', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');

            $table->string('name',50)->nullable();
            $table->string('mobile',50)->nullable();
            $table->string('email',50)->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',50)->nullable();
            $table->integer('country')->nullable();
            $table->string('zip_code',50)->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('user_shipping_address');
    }
}
