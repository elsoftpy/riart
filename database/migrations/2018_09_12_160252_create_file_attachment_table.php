<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_attachments', function(Blueprint $table){
            $table->increments('id');
            $table->integer('rubro_id')->unsigned();
            $table->string('periodo');
            $table->string('filename');
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
        //
    }
}
