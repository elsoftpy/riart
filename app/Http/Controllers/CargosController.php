<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cargo;
use App\Area;
use App\Nivel;
use flash;

class CargosController extends Controller
{
    public function index(){
    	$dbData = Cargo::get();
    	return view('cargos.list')->with('dbData', $dbData);
    }

    public function create(){
		$dbNivel = Nivel::all()->pluck('descripcion', 'id');
		$dbArea = Area::all()->pluck('descripcion', 'id');
    	return view('cargos.create')->with('dbNivel', $dbNivel)
    								->with('dbArea', $dbArea);
    }

    public function store(Request $request){
    	$dbData = new Cargo($request->all());
    	$dbData->save();
    	return redirect()->route('cargos.index');
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
    	$dbData->save();
		return redirect()->route('cargos.index');
    }

    public function destroy($id){
		$dbData = Cargo::find($id);
        $dbFuncionario = $dbData->funcionario;

        if($dbFuncionario->count() > 0 ){
            Flash::elsoftMessage(3015, true);
        }else{
            Flash::elsoftMessage(2015, true);
            $dbData->delete();            
        }        
		return redirect()->route('cargos.index');    	
    }

    public function getDetalle(Request $request){
        $cargo = Cargo::find($request->id);
        return $cargo->detalle;
    }


}
