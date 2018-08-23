<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_composicion_item extends Model
{
    protected $table = "beneficios_composicion_items";

    protected $fillable = ["titulo", "rubro_id", "beneficios_pregunta_id"];

    public function rubro(){
    	return $this->belongsTo('App\Rubro');
    }
    
    public function pregunta(){
    	return $this->belongsTo('App\beneficios_pregunta', 'beneficios_pregunta_id');
    }    }
