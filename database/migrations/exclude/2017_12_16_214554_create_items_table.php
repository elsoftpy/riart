<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficios_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->integer('categoria_id')->unsigned();
            $table->integer('beneficios_pregunta_id')->unsigned();
            $table->timestamps();

            $table->foreign('categoria_id')
                  ->references('id')
                  ->on('categorias')
                  ->onUpddate('cascade')
                  ->onDelete('restrict');

            $table->foreign('beneficios_pregunta_id')
                  ->references('id')
                  ->on('beneficios_preguntas')
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
        Schema::dropIfExists('items');
    }
}
