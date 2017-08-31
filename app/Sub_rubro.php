<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_rubro extends Model
{
    protected $table = "sub_rubros";

    protected $fillable = ["descripcion", "rubro_id"];

    public function rubro(){
    	return $this->belongsTo("App\Rubro");
    }

    public function cabeceraEncuesta(){
    	return $this->hasMany("App\Cabecera_encuesta");
    }

}
