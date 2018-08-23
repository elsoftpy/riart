<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ficha_dato extends Model
{
    protected $table = 'ficha_datos';

    protected $fillable = ['rubro_id', 'periodo', 'tipo_cambio', 'cargos_emergentes'];

    public function rubro(){
    	return $this->belongsTo('App\Rubro');
    }
}
