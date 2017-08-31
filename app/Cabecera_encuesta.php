<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cabecera_encuesta extends Model
{
    protected $table = "cabecera_encuestas";

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

    public function encuestasCargo(){
        return $this->hasMany("App\Encuestas_cargo");
    }

    public function detalleEncuesta(){
        return $this->hasMany("App\Detalle_encuesta");
    }
}
