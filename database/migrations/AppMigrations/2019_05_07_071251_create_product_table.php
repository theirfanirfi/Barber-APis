<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('product_name');
            $table->string('product_image');
            $table->float('product_price');
            $table->integer('cat_id')->unsigned();
            $table->integer('quantity');
            $table->integer('sold');
            $table->integer('discount');
            $table->integer('available');
            $table->timestamps();
            $table->foreign('cat_id')->references('cat_id')->on('categories');
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
