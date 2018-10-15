<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area_en extends Model
{
    protected $table = "areas_en";

    protected $fillable = ["descripcion"];

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }
}
