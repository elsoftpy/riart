<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComposicionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composicion_items', function (Blueprint $table) {
             $table->increments('id');
            $table->string('titulo');
            $table->integer('rubro_id')->unsigned()->nullable();
            $table->integer('beneficios_pregunta_id')->unsigned();
            $table->timestamps();

            $table->foreign('rubro_id')
                  ->references('id')
                  ->on('rubros')
                  ->onUpddate('cascade')
                  ->onDelete('restrict');

            $table->foreign('beneficios_pregunta_id')
                  ->references('id')
                  ->on('beneficios_preguntas')
                  ->onUpddate('cascade')
                  ->onDelete('restrict'); 
        });

        Schema::table('items', function(Blueprint $table){
            $table->integer('rubro_id')->unsigned()->nullable();

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
        Schema::dropIfExists('composicion_items');
    }
}
