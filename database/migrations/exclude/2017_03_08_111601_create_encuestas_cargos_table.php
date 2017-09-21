<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncuestasCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encuestas_cargos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion', 100);
            $table->integer('cabecera_encuesta_id')->unsigned();
            $table->integer('cargo_id')->unsigned()->nullable();
            $table->integer('incluir')->default(1);
            $table->integer('revisado')->default(0);
            $table->timestamps();

            $table->foreign('cabecera_encuesta_id')
                  ->references('id')
                  ->on('cabecera_encuestas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('cargo_id')
                  ->references('id')
                  ->on('cargos')
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
        Schema::dropIfExists('encuestas_cargos');
    }
}
