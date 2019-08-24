<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = "rubros";

    protected $fillable = ["descripcion"];

    public function subRubro(){
    	return $this->hasMany("App\Sub_rubro");
    }

    public function empresa(){
    	return $this->hasMany("App\Empresa");
    }

    public function cabeceraEncuesta(){
    	return $this->hasMany("App\Cabecera_encuesta");
    }

}
