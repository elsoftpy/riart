<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autos_modelo extends Model
{
    protected $table = "autos_modelos";

    protected $fillable = ["descripcion", "autos_marca_id"];

    public function modelo(){
    	return $this->belongsTo("App\Autos_marca");
    }

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }

}
