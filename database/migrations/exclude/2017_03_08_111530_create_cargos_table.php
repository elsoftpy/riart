<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion', 100);
            $table->integer('area_id')->unsigned();
            $table->integer('nivel_id')->unsigned();
            $table->timestamps();

            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('nivel_id')
                  ->references('id')
                  ->on('niveles')
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
        Schema::dropIfExists('cargos');
    }
}
