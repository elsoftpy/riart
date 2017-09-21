<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_pregunta extends Model
{
 	protected $table = "beneficios_preguntas";

    protected $fillable = ["pregunta", "cerrada"];

    public function beneficiosOpcion(){
    	return $this->hasMany('App\beneficios_opcion')
    }

}
