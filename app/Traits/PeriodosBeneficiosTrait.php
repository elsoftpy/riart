<?php

namespace App\Traits;

use App\Rubro;
use App\beneficios_cabecera_encuesta;

trait PeriodosBeneficiosTrait{
    public function getRubros(){
        // recuperamos todos los rubros
        $rubros = Rubro::all()->pluck('descripcion', 'id');
        return $rubros;
    }
    
    public function getPeriodos($id){
        // recuperamos todas las encuestas del primer rubro recuperado
        $periodos = beneficios_cabecera_encuesta::where('rubro_id', $id)->get();
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