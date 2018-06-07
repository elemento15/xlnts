<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('sale_date');
            $table->integer('client_id')->unsigned();
            $table->decimal('subtotal', 12, 4)->default(0);
            $table->boolean('has_invoice')->default(0);
            $table->decimal('iva_percent', 5, 2)->default(0);
            $table->decimal('iva_amount', 12, 4)->default(0);
            $table->decimal('total', 12, 4)->default(0);
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
        });
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
