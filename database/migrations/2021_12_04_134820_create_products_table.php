<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('model_number')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('currency',10)->nullable();
            $table->string('currency_icon',5)->nullable();
            $table->decimal('price',9,2);
            $table->decimal('regular_price',9,2)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('total_sale')->default(0);
            $table->integer('brand')->nullable();
            $table->integer('model')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('gallery')->nullable();
            $table->text('options')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('products');
    }
}
