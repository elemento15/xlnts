<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('visit_id')->unsigned();
            $table->enum('type', ['L','R']);
            $table->integer('attribute_id')->unsigned();
            $table->string('value', 20);

            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('restrict');
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
        Schema::dropIfExists('visit_attributes');
    }
}
