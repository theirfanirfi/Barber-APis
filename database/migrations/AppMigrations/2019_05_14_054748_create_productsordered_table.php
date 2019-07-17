<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsorderedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('Order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('checkout_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('product_price');
            $table->integer('quantity_ordered');
            $table->integer('is_shipment_charges')->default(0);
            $table->integer('shipment_charges')->default(0);
            $table->timestamps();
            $table->foreign('checkout_id')->references('id')->on('checkout')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Order');
    }
}
