<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiosConclusionAbiertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficios_conclusion_abiertas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('beneficios_pregunta_id')->unsigned();
            $table->integer('rubro_id')->unsigned();
            $table->string('periodo');
            $table->string('conclusion', 3000);
            $table->timestamps();

            $table->foreign('beneficios_pregunta_id')
                  ->references('id')
                  ->on('beneficios_preguntas')
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
        Schema::dropIfExists('beneficios_conclusion_abiertas');
    }
}
