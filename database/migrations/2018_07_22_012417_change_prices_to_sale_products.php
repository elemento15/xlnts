<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePricesToSaleProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->decimal('product_price', 12, 4)->default(0)->after('quantity');
            $table->decimal('saved_price', 12, 4)->default(0)->after('product_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->decimal('price', 12, 4)->default(0)->after('quantity');
            $table->dropColumn('product_price');
            $table->dropColumn('saved_price');
        });
    }
}
