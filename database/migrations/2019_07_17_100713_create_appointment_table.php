<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->integer('hour');
            $table->integer('minutes');
            $table->integer('time_modulation');
            $table->integer('time_in_milli');
            $table->bigInteger('user_id')->default(0)->unsigned(); //customer id.
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
        Schema::dropIfExists('appointments');
    }
}
