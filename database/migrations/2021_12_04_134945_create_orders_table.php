<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->integer('user_id');

            $table->string('currency_code',20);
            $table->string('currency_sign',20);
            
            $table->decimal('cart_total_amount',9,2)->default(0);
            $table->decimal('total_amount',9,2)->default(0);
            
            $table->text('coupon_id')->nullable();
            $table->text('delivery_option_id')->nullable();

            $table->text('shipping_address');
            $table->text('billing_address')->nullable();

            $table->integer('transaction_id')->nullable();

            $table->integer('canceled_by')->default(0);
            $table->dateTime('canceled_on')->nullable();
            
            $table->boolean('adminView')->default(0);
            $table->integer('order_status')->default(0);
            
            $table->integer('payment_status')->default(0);
            $table->boolean('user_trashed')->default(false);
            
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
