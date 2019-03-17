<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_periodo extends Model
{
    protected $table = "beneficios_periodos";

    protected $fillable = ["periodo", "rubro_id", "activo"];
    
    public function rubro(){
    	return $this->belongsTo('App\Rubro');
    }

    public function scopeActivo($query){
        return $query->where('activo', 1);
    }    
}
