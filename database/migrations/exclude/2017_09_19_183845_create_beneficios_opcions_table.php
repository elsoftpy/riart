<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiosOpcionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficios_opciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('beneficios_pregunta_id')->unsigned();
            $table->string('opcion', 200);
            $table->timestamps();

            $table->foreign('beneficios_pregunta_id')
                  ->references('id')
                  ->on('beneficios_preguntas')
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
        Schema::table('beneficios_opciones', function(Blueprint $table){
            $table->dropForeign(['beneficios_pregunta_id']);
        }); 

        Schema::dropIfExists('beneficios_opciones');
    }
}
