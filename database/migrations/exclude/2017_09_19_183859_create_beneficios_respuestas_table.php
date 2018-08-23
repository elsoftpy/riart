<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiosRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficios_respuestas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('beneficios_pregunta_id')->unsigned();
            $table->integer('beneficios_opcion_id')->unsigned()->nullable();
            $table->string('abierta', 1000)->nullable();
            $table->timestamps();
            
            $table->foreign('beneficios_pregunta_id')
                  ->references('id')
                  ->on('beneficios_preguntas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');            

            $table->foreign('beneficios_opcion_id')
                  ->references('id')
                  ->on('beneficios_opciones')
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
        Schema::table('beneficios_respuestas', function(Blueprint $table){
            $table->dropForeign(['beneficios_pregunta_id']);
            $table->dropForeign(['beneficios_opcion_id']);
        }); 

        Schema::dropIfExists('beneficios_respuestas');
    }
}
