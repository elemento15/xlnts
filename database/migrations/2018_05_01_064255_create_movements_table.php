<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('mov_date');
            $table->integer('movement_concept_id')->unsigned();
            $table->enum('type', ['E','S']);
            $table->boolean('active')->default(1);
            $table->datetime('cancel_date')->nullable();
            $table->string('cancel_info')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('movement_concept_id')->references('id')->on('movement_concepts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
}
