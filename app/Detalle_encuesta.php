<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_encuesta extends Model
{
    protected $table = "detalle_encuestas";

    protected $fillable = ['encuesta_cabecera_id',
							'encuestas_cargo_id',
							'cantidad_ocupantes',
							'area_id',
							'nivel_id',
							'salario_base',
							'cantidad_salarios',
							'gratificacion',
							'aguinaldo',
							'comision',
							'cantidad_comision',
							'plus_rendimiento',
							'cantidad_plus_rendimiento',
							'fallo_caja',
							'cantidad_fallo_caja',
							'fallo_caja_ext',
							'cantidad_fallo_caja_ext',            
							'gratificacion_contrato',
							'cantidad_gratificacion_contrato',
							'adicional_nivel_cargo',
							'cantidad_adicional_nivel_cargo',
							'adicional_titulo',
							'cantidad_adicional_titulo',
							'adicional_amarre',
							'cantidad_adicional_amarre',
							'adicional_tipo_combustible',
							'cantidad_adicional_tipo_combustible',
							'adicional_embarque',
							'cantidad_adicional_embarque',
							'adicional_carga',
							'cantidad_adicional_carga',
							'bono_anual',
							'bono_anual_salarios',
							'incentivo_largo_plazo',
							'refrigerio',
							'costo_seguro_medico',
							'cobertura_seguro_medico',
							'costo_seguro_vida',
							'costo_poliza_muerte_natural',
							'costo_poliza_muerte_accidente',
							'aseguradora_id',
							'car_company',
							'movilidad_full',
							'flota',
							'autos_marca_id',
							'autos_modelo_id',
							'tarjeta_flota',
							'monto_movil',
							'seguro_movil',
							'mantenimiento_movil',
							'monto_km_recorrido',
							'monto_ayuda_escolar',
							'monto_comedor_interno',
							'monto_curso_idioma',
							'cobertura_curso_idioma',
							'tipo_clase_idioma',
							'monto_post_grado',
							'cobertura_post_grado',
							'monto_celular_corporativo',
							'monto_vivienda',
							'monto_colegiatura_hijos',
							'condicion_ocupante',
							'zona_id'];
	
	public function cabeceraEncuesta(){
		return $this->belongsTo("App\Cabecera_encuesta");
	}

	public function encuestasCargo(){
		return $this->belongsTo("App\Encuestas_cargo");
	}

	public function area(){
		return $this->belongsTo("App\Area");
	}

	public function aseguradora(){
		return $this->belongsTo("App\Aseguradora");
	}

	public function autosMarca(){
		return $this->belongsTo("App\Autos_marca");
	}

	public function autosModelo(){
		return $this->belongsTo("App\Autos_modelo");
	}

	public function zona(){
		return $this->belongsTo("App\Zonas");
	}

	public function getBeneficiosBancosAttribute(){
		$beneficios = 	$this->refrigerio + 
						$this->costo_seguro_medico * ($this->cobertura_seguro_medico/100) + 
						$this->costo_seguro_vida + 
						//$this->costo_poliza_muerte_accidente +
						//$this->costo_poliza_muerte_natural +
						$this->monto_movil / 60 +
						$this->flota+
						$this->seguro_movil +
						$this->monto_km_recorrido +
						$this->monto_ayuda_escolar +
						$this->monto_comedor_interno +
						$this->monto_curso_idioma * ($this->cobertura_curso_idioma/100)/12 +
						$this->monto_post_grado * ($this->cobertura_post_grado/100)/ 24 +
						$this->monto_celular_corporativo +
						$this->monto_vivienda +
						$this->monto_colegiatura_hijos / 12;

		return $beneficios;
	}

	public function getBeneficiosNavierasAttribute(){
		$beneficios = 	$this->refrigerio + 
						$this->costo_seguro_medico + 
						$this->costo_seguro_vida + 
						//$this->costo_poliza_muerte_accidente +
						//$this->costo_poliza_muerte_natural +
						$this->monto_movil / 60 +
						$this->flota+
						$this->seguro_movil +
						$this->monto_km_recorrido +
						$this->monto_ayuda_escolar +
						$this->monto_comedor_interno +
						$this->monto_curso_idioma * $this->cobertura_curso_idioma +
						$this->monto_post_grado / 24 +
						$this->monto_celular_corporativo +
						$this->monto_vivienda +
						$this->monto_colegiatura_hijos;

		return $beneficios;
	}	
}