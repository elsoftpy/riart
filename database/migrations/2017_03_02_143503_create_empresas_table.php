<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion', 100);
            $table->integer('cantidad_empleados');
            $table->integer('cantidad_sucursales');
            $table->integer('tipo');
            $table->integer('rubro_id')->unsigned();
            $table->integer('sub_rubro_id')->unsigned();
            $table->timestamps();

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
        Schema::dropIfExists('empresas');
    }
}
