<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivel_en extends Model
{
    protected $table = "niveles_en";

    protected $fillable = ["descripcion"];

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }
}
