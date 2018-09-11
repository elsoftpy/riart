<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ficha_dato;
use App\Cabecera_encuesta;
use App\Rubro;
use flash;

class FichasController extends Controller
{
    public function index(){
        dd("holo");
    	$dbData = Ficha_dato::get();
    	return view('fichas.list')->with('dbData', $dbData);
    }

    public function create(){
		$periodos = Cabecera_encuesta::get();
        $periodos = $periodos->map(function($item){
            $rubro = $item->rubro->descripcion;
            $periodo = $item->periodo;
            $item['periodo_combo'] = $periodo.' - '.$rubro;
            return $item;
        })->unique('periodo')->pluck('periodo_combo', 'periodo');
		$rubros = Rubro::all()->pluck('descripcion', 'id');
    	return view('fichas.create')->with('periodos', $periodos)
    								->with('rubros', $rubros);
    }

    public function store(Request $request){
    	$dbData = new Ficha_dato($request->all());

    	$dbData->save();
    	return redirect()->route('admin_ficha.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = Cargo::find($id);
        $dbNivel = Nivel::all()->pluck('descripcion', 'id');
        $dbArea = Area::all()->pluck('descripcion', 'id');
       	return view('cargos.edit')->with('dbData', $dbData)
                                  ->with('dbNivel', $dbNivel)
                                  ->with('dbArea', $dbArea);
    }

    public function update(Request $request, $id){

    	$dbData = Cargo::find($id);
    	$dbData->fill($request->all());
        if(!is_null($request->is_temporal)){
            $dbData->is_temporal = 1;
        }else{
            $dbData->is_temporal = 0;
        }
    	$dbData->save();
		return redirect()->route('cargos.index');
    }

    public function destroy($id){
		$dbData = Cargo::find($id);
        $dbData->delete();            

		return redirect()->route('cargos.index');    	
    }

    public function getDetalle(Request $request){
        $cargo = Cargo::find($request->id);
        return $cargo->detalle;
    }


}
