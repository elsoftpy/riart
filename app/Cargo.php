<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = "cargos";

    protected $fillable = ["descripcion", "area_id", "nivel_id", "is_temporal", "detalle"];

    public function encuestasCargo(){
        return $this->hasMany("App\Encuestas_cargo");
    }

    public function area(){
    	return $this->belongsTo("App\Area");
    }

    public function nivel(){
    	return $this->belongsTo("App\Nivel");
    }    
}
