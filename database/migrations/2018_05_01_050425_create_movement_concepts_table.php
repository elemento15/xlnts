<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementConceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_concepts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code', 10)->unique()->nullable();
            $table->enum('type', ['E','S']);
            $table->boolean('is_auto')->default(0);
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('movement_concepts');
    }
}
