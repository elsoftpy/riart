<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_opcion extends Model
{
 	protected $table = "beneficios_opciones";

    protected $fillable = ["beneficios_pregunta_id", "opcion"];

    public function beneficioPregunta(){
    	return $this->belongsTo('App\beneficios_pregunta');
    }

}
