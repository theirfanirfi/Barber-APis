<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessengerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sender_id')->unsigned()->default(0);
            $table->bigInteger('reciever_id')->unsigned()->default(0);
            $table->string('msg')->nullable();
            $table->bigInteger('p_id')->unsigned()->default(0); //participants id
            $table->boolean('is_read')->default(0); // 0 and 1
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reciever_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('p_id')->references('id')->on('participants')->onDelete('cascade');
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
        Schema::dropIfExists('messenger');
    }
}
