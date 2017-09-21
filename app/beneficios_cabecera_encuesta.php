<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_cabecera_encuesta extends Model
{
    protected $table = "beneficios_cabecera_encuestas";

    protected $fillable = ["empresa_id", "rubro_id", "sub_rubro_id", "cantidad_empleados", "cantidad_sucursales", "periodo", "finalizada"];

    public function empresa(){
    	return $this->belongsTo('App\Empresa');
    }

    public function rubro(){
    	return $this->belongsTo('App\Rubro');	
    }
	
	public function subRubro(){
    	return $this->belongsTo('App\Sub_rubro');	
    }    

    public function detalleBeneficio(){
        return $this->hasMany("App\beneficios_respuesta");
    }
}
