<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = "areas";

    protected $fillable = ["descripcion"];

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }
}
