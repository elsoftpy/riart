<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = "empresas";

    protected $fillable = ["descripcion", "cantidad_empleados", "cantidad_sucursales", "tipo", "rubro_id", "sub_rubro_id"];


    public function rubro(){
    	return $this->belongsTo("App\Rubro");
    }

    public function subRubro(){
    	return $this->belongsTo("App\Sub_rubro");
    }

    public function cabeceraEncuesta(){
    	return $this->hasMany("App\Cabecera_encuesta");
    }

}
