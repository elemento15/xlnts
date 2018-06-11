<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesToVisitAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_attributes', function (Blueprint $table) {
            $table->decimal('left_value', 8, 2)->default(0);
            $table->decimal('right_value', 8, 2)->default(0);
            $table->dropColumn('type');
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_attributes', function (Blueprint $table) {
            $table->dropColumn('left_value');
            $table->dropColumn('right_value');
            $table->enum('type', ['L','R']);
            $table->string('value', 20);
        });
    }
}
