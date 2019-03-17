<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiosPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficios_periodos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('periodo');
            $table->integer('rubro_id')->unsigned();
            $table->integer('activo');
            $table->timestamps();

            $table->foreign('rubro_id')
            ->references('id')
            ->on('rubros')
            ->onDelete('restrict')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficios_periodos');
    }
}
