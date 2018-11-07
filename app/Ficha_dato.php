<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ficha_dato extends Model
{
    protected $table = 'ficha_datos';

    protected $fillable = ['rubro_id', 'periodo', 'tipo_cambio', 'cargos_emergentes', 'activo'];

    public function rubro(){
    	return $this->belongsTo('App\Rubro');
    }

    public function scopeActiva($query){
        return $query->where('activo', 1);
    }
}
