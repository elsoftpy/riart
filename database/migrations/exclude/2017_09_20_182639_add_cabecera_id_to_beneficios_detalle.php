<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCabeceraIdToBeneficiosDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beneficios_respuestas', function (Blueprint $table) {
            $table->integer('beneficios_cabecera_encuesta_id')->after('id')->unsigned();

            $table->foreign('beneficios_cabecera_encuesta_id')
                  ->references('id')
                  ->on('beneficios_cabecera_encuestas')
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
            $table->dropForeign(['beneficios_cabecera_encuesta_id']);
            $table->dropColumn('beneficios_cabecera_encuesta_id');
        }); 

    }
}
