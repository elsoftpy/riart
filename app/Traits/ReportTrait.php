<?php

namespace App\Traits;

use App\Rubro;
use App\Cabecera_encuesta;

trait ReportTrait{
    public function getRubros(){
        // recuperamos todos los rubros
        $rubros = Rubro::all()->pluck('descripcion', 'id');
        return $rubros;
    }
    
    public function getPeriodos($id){
        // recuperamos todas las encuestas del primer rubro recuperado
        $periodos = Cabecera_encuesta::where('rubro_id', $id)->get();
        // filtramos los periodos para el primer rubro recuperado
        $periodos = $periodos->map(function($item){
            $rubro = $item->rubro->descripcion;
            $periodo = $item->periodo;
            $item['periodo_combo'] = $periodo.' - '.$rubro;
            return $item;            
        })->unique('periodo')->pluck('periodo_combo', 'periodo');

        return $periodos;
    }
}