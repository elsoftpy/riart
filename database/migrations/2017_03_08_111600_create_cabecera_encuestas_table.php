<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCabeceraEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabecera_encuestas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresa_id')->unsigned();
            $table->integer('rubro_id')->unsigned();
            $table->integer('sub_rubro_id')->unsigned();
            $table->integer('cantidad_empleados');
            $table->integer('cantidad_sucursales');
            $table->string('periodo', 20);
            $table->string('finalizada', 1)->default('N');
            $table->timestamps();

            $table->foreign('empresa_id')
                  ->references('id')
                  ->on('empresas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('rubro_id')
                  ->references('id')
                  ->on('rubros')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('sub_rubro_id')
                  ->references('id')
                  ->on('sub_rubros')
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
        Schema::dropIfExists('cabecera_encuestas');
    }
}
