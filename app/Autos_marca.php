<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autos_marca extends Model
{
    protected $table = "autos_marcas";

    protected $fillable = ["descripcion"];

    public function modelo(){
    	return $this->hasMany("App\Autos_modelo");
    }

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }
}
