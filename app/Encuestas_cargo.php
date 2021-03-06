<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuestas_cargo extends Model
{
    protected $table = "encuestas_cargos";

    protected $fillable = ['descripcion', 'cabecera_encuesta_id', 'cargo_id', 'incluir', 'revisado', 'es_contrato_periodo'];

    protected $casts = ["incluir" => "boolean", "revisado"=>"boolean"];

    public function cabeceraEncuestas(){
    	return $this->belongsTo("App\Cabecera_encuesta", "cabecera_encuesta_id");
    }

    public function cargo(){
    	return $this->belongsTo("App\Cargo");
    }    

    public function detalleEncuestas(){
    	return $this->hasOne("App\Detalle_encuesta");
    }
}
