<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip',100);
            $table->string('country',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('device',100)->nullable();
            $table->string('device_os',100)->nullable();
            $table->string('browser',100)->nullable();
            $table->string('session',255)->nullable();
            $table->integer('total_visiting')->default(1);
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
        Schema::dropIfExists('visitors');
    }
}
