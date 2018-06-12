<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_product_id')->unsigned();
            $table->integer('attribute_id')->unsigned();
            $table->decimal('left_value', 8, 2)->default(0);
            $table->decimal('right_value', 8, 2)->default(0);

            $table->foreign('sale_product_id')->references('id')->on('sale_products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_product_attributes');
    }
}
