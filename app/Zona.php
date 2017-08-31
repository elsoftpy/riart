<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = "zonas";

    protected $fillable = ["descripcion"];

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }
}
