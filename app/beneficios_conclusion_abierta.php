<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_conclusion_abierta extends Model
{
    protected $table = "beneficios_conclusion_abiertas";

    protected $fillable = ["beneficios_pregunta_id", "rubro_id", "conclusion", "periodo"];

    public function pregunta(){
    	return $this->belongsTo("App\beneficios_pregunta");
    }

    public function rubro(){
    	return $this->belongsTo("App\Rubro");
    }    
}
