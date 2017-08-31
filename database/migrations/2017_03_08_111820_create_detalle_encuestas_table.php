<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_encuestas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('encuesta_cabecera_id')->unsigned();
            $table->integer('encuestas_cargo_id')->unsigned();
            $table->integer('cantidad_ocupantes');
            $table->integer('area_id')->unsigned()->nullable();
            $table->integer('nivel_id')->unsigned()->nullable();
            $table->integer('salario_base');
            $table->integer('cantidad_salarios')->nullable();
            $table->integer('gratificacion')->nullable();
            $table->integer('aguinaldo');
            $table->integer('comision')->nullable();
            $table->integer('cantidad_comision')->nullable();
            $table->integer('plus_rendimiento')->nullable();
            $table->integer('cantidad_plus_rendimiento')->nullable();
            $table->integer('fallo_caja')->nullable();
            $table->integer('cantidad_fallo_caja')->nullable();
            $table->integer('fallo_caja_ext')->nullable();
            $table->integer('cantidad_fallo_caja_ext')->nullable();            
            $table->integer('gratificacion_contrato')->nullable();
            $table->integer('cantidad_gratificacion_contrato')->nullable();
            $table->integer('adicional_nivel_cargo')->nullable();
            $table->integer('cantidad_adicional_nivel_cargo')->nullable();
            $table->integer('adicional_titulo')->nullable();
            $table->integer('cantidad_adicional_titulo')->nullable();
            $table->integer('adicional_amarre')->nullable();
            $table->integer('cantidad_adicional_amarre')->nullable();
            $table->integer('adicional_tipo_combustible')->nullable();
            $table->integer('cantidad_adicional_tipo_combustible')->nullable();
            $table->integer('adicional_embarque')->nullable();
            $table->integer('cantidad_adicional_embarque')->nullable();
            $table->integer('adicional_carga')->nullable();
            $table->integer('cantidad_adicional_carga')->nullable();
            $table->integer('bono_anual')->nullable();
            $table->integer('bono_anual_salarios')->nullable();
            $table->integer('incentivo_largo_plazo')->nullable();
            $table->integer('refrigerio')->nullable();
            $table->integer('costo_seguro_medico')->nullable();
            $table->integer('cobertura_seguro_medico')->nullable();
            $table->integer('costo_seguro_vida')->nullable();
            $table->integer('costo_poliza_muerte_natural')->nullable();
            $table->integer('costo_poliza_muerte_accidente')->nullable();
            $table->integer('aseguradora_id')->unsigned()->nullable();
            $table->integer('car_company')->nullable();
            $table->integer('movilidad_full')->nullable();
            $table->integer('flota')->nullable();
            $table->integer('autos_marca_id')->unsigned()->nullable();
            $table->integer('autos_modelo_id')->unsigned()->nullable();
            $table->integer('tarjeta_flota')->nullable();
            $table->integer('seguro_movil')->nullable();
            $table->integer('monto_movil')->nullable();
            $table->integer('mantenimiento_movil')->nullable();
            $table->integer('monto_km_recorrido')->nullable();
            $table->integer('monto_ayuda_escolar')->nullable();
            $table->integer('monto_comedor_interno')->nullable();
            $table->integer('monto_curso_idioma')->nullable();
            $table->integer('cobertura_curso_idioma')->nullable();
            $table->string('tipo_clase_idioma')->default("I");
            $table->integer('monto_post_grado')->nullable();
            $table->integer('cobertura_post_grado')->nullable();
            $table->integer('monto_celular_corporativo')->nullable();
            $table->integer('monto_vivienda')->nullable();
            $table->integer('monto_colegiatura_hijos')->nullable();
            $table->string('condicion_ocupante')->default('L');
            $table->integer('zona_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('encuesta_cabecera_id')
                  ->references('id')
                  ->on('cabecera_encuestas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); 

            $table->foreign('encuestas_cargo_id')
                  ->references('id')
                  ->on('encuestas_cargos')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                                                     

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

            $table->foreign('aseguradora_id')
                  ->references('id')
                  ->on('aseguradoras')
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); 

            $table->foreign('autos_marca_id')
                  ->references('id')
                  ->on('autos_marcas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                                   

            $table->foreign('autos_modelo_id')
                  ->references('id')
                  ->on('autos_modelos')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');   

            $table->foreign('zona_id')
                  ->references('id')
                  ->on('zonas')
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
        Schema::dropIfExists('detalle_encuestas');
    }
}
