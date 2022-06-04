<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report', function (Blueprint $table) {
            $table->integer('total_orders')->default(0);
            $table->integer('total_completed_orders')->default(0);
            $table->integer('total_sale_amount')->default(0);
            $table->integer('total_return')->default(0);
        });
        // Insert some stuff
        DB::table('sales_report')->insert(array('total_orders'=>0));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
