<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = "niveles";

    protected $fillable = ["descripcion"];

    public function detalleEncuestas(){
    	return $this->hasMany("App\Detalle_encuesta");
    }

    public function nivelEn(){
        return $this->hasOne('App\Nivel_en', 'id' , 'id');
    }
}
