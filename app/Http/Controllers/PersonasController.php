<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\persona;
use flash;

class PersonasController extends Controller
{
    public function index(){
    	$dbData = Persona::get();
    	return view('personas.list')->with('dbData', $dbData);
    }

    public function create(){
    	return view('personas.create');
    }

    public function store(Request $request){
    	$dbData = new Persona($request->all());
    	$dbData->save();
    	return redirect()->route('personas.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = Persona::find($id);
    	if($dbData->estado == 'A'){
    		$estado = true;
    	}else{
    		$estado = false;
    	}

    	return view('personas.edit')->with('dbData', $dbData)->with('estado', $estado);
    }

    public function update(Request $request, $id){

    	$dbData = Persona::find($id);

    	$dbData->fill($request->all());
    	if(isset($request->estado)){
    		$dbData->estado = 'A';
    	}else{
			$dbData->estado = 'I';
    	}
    	
    	$dbData->save();
		return redirect()->route('personas.index');
    }

    public function destroy($id){
		$dbData = Persona::find($id);
        if(is_null($dbData->user) && is_null($dbData->funcionario)){
            Flash::elsoftMessage(2011, true);
            $dbData->delete();            
        }else{
            Flash::elsoftMessage(3011, true);
        }


		return redirect()->route('personas.index');    	
    }
}
