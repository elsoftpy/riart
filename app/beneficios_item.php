<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_item extends Model
{
    protected $table = "beneficios_items";

    protected $fillable = ["titulo", "categoria_id", "beneficios_pregunta_id", "rubro_id"];

    public function categoria(){
    	return $this->belongsTo('App\beneficios_categoria', "id", "categoria_id");
    }
    
    public function pregunta(){
    	return $this->belongsTo('App\beneficios_pregunta', 'beneficios_pregunta_id');
    }    

 	public function rubro(){
    	return $this->belongsTo('App\Rubro');
    }
}
