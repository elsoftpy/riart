<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBeneficiosPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beneficios_preguntas', function (Blueprint $table) {
            $table->integer('multiple')->after('cerrada')->default(0);
            $table->integer('beneficio')->after('multiple')->default(0);
            $table->integer('beneficios_pregunta_id')->after('beneficio')->unsigned()->nullable();

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
        //
    }
}
