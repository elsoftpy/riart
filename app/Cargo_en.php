<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo_en extends Model
{
    protected $table = "cargos_en";

    protected $fillable = ["descripcion", "area_id", "nivel_id", "is_temporal", "detalle"];

    public function encuestasCargo(){
        return $this->hasMany("App\Encuestas_cargo");
    }

    public function cargosRubro(){
        return $this->hasMany("App\Cargos_rubro");
    }

    public function area(){
    	return $this->belongsTo("App\Area");
    }

    public function nivel(){
    	return $this->belongsTo("App\Nivel_en");
    }

}
