<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRubroIdToBeneficiosPreguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beneficios_preguntas', function (Blueprint $table) {
            $table->integer('rubro_id')->after('beneficios_pregunta_id')->unsigned()->nullable();

            $table->foreign('rubro_id')
                  ->references('id')
                  ->on('rubros')
                  ->onUpddate('cascade')
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
        //
    }
}
