<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('movement_id')->unsigned();
            $table->decimal('quantity', 10, 2)->default(0);
            // $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('movement_id')->references('id')->on('movements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movement_products');
    }
}
