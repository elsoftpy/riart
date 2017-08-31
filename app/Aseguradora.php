<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aseguradora extends Model
{
    protected $table = "aseguradoras";

    protected $fillable = ["descripcion"];

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }
}
