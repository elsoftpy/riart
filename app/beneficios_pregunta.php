<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_pregunta extends Model
{
 	protected $table = "beneficios_preguntas";

    protected $fillable = ["pregunta", "cerrada", "multiple", "beneficio", "beneficios_pregunta_id", "orden", "rubro_id", "activa"];

    protected $casts = ["multiple"=>"boolean", "beneficio"=>"boolean", "activo"=>"boolean"];

    public function beneficiosOpcion(){
    	return $this->hasMany('App\beneficios_opcion');
    }

    public function beneficiosPregunta(){
    	return $this->belongsTo('App\beneficios_pregunta');
    }    

    public function item(){
    	return $this->hasOne('App\beneficios_item');
    }

    public function itemComposicion(){
        return $this->hasOne('App\beneficios_composicion_item');
    }

    public function getTituloAttribute(){
        return $this->item->titulo;
    }

    public function scopeCerrada($query){
        return $query->where('cerrada', 'S');
    }

    public function scopeAbierta($query){
        return $query->where('cerrada', 'N');
    }

    public function scopeActiva($query){
        return $query->where('activa', '1');
    }
}
