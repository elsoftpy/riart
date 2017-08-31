<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\gerencia;
use flash;
class GerenciasController extends Controller
{
    public function index(){
    	$dbData = Gerencia::get();
    	return view('gerencias.list')->with('dbData', $dbData);
    }

    public function create(){
    	return view('gerencias.create');
    }

    public function store(Request $request){
    	$dbData = new Gerencia($request->all());
    	$dbData->save();
    	return redirect()->route('gerencias.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = Gerencia::find($id);

       	return view('gerencias.edit')->with('dbData', $dbData);
    }

    public function update(Request $request, $id){

    	$dbData = Gerencia::find($id);
    	$dbData->fill($request->all());
    	$dbData->save();
		return redirect()->route('gerencias.index');
    }

    public function destroy($id){
	    $dbData = Gerencia::find($id);
        $dbDepartamento = $dbData->departamento;
        $dbSector = $dbData->sector;
        $dbCargo = $dbData->cargo;

        if($dbDepartamento->count() > 0 || $dbSector->count() > 0 || $dbCargo->count() > 0){
            Flash::elsoftMessage(3012, true);
        }else{
            Flash::elsoftMessage(2012, true);
            $dbData->delete();            
        }
		return redirect()->route('gerencias.index');    	
    }
}
