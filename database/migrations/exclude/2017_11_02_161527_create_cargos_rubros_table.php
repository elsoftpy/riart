<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargosRubrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargos_rubros', function (Blueprint $table) {
            $table->integer('cargo_id')->unsigned();
            $table->integer('rubro_id')->unsigned();

            $table->foreign('cargo_id')
                  ->references('id')
                  ->on('cargos')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');            

            $table->foreign('rubro_id')
                  ->references('id')
                  ->on('rubros')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                              

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargos_rubros', function (Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropForeign(['rubro_id']);
        });    
        Schema::dropIfExists('cargos_rubros');
    }
}
