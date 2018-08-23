<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_respuesta extends Model
{
 	protected $table = "beneficios_respuestas";

    protected $fillable = ['beneficios_pregunta_id', 'beneficios_opcion_id', 'abierta'];

    public function beneficiosPregunta(){
    	return $this->belongsTo('App\beneficios_pregunta');
    }

    public function beneficiosOpcion(){
    	return $this->belongsTo('App\beneficios_opcion');
    }

    public function marcaOpcion(){
    	return $this->belongsTo('App\Autos_marca', 'beneficios_opcion_id');    	
    }

    public function modeloOpcion(){
    	return $this->belongsTo('App\Autos_modelo', 'beneficios_opcion_id');    	    	
    }

    public function aseguradoraOpcion(){
    	return $this->belongsTo('App\Aseguradora', 'beneficios_opcion_id');    	    	
    }    
}
