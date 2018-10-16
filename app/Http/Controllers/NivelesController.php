<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Nivel;
use App\Nivel_en;
use flash;
use DB;

class NivelesController extends Controller
{
    public function index(){
        $dbData = Nivel::get();
        // Si terminó de borrar el registro
        $toast = session('delete_failed');
        if($toast){
            session()->forget('delete_failed');
        }
    	return view('niveles.list')->with('dbData', $dbData)->with('toast', $toast);
    }

    public function create(){
        return view('niveles.create');
    }

    public function store(Request $request){
        DB::transaction(function() use($request){
            //Cargamos el área en español
            $dbData = new Nivel($request->all());
            //Cargamos el área en inglés  
            $dbDataEn = new Nivel_en();
            $dbDataEn->descripcion = $request->descripcion_en;
            //Guardamos los registros
            $dbData->save();
            $dbDataEn->save();            
        });

    	return redirect()->route('niveles.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = nivel::find($id);
       	return view('niveles.edit')->with('dbData', $dbData);
    }

    public function update(Request $request, $id){
        
        //nivel en español
        $dbData = Nivel::find($id);
        //nivel en inglés
        $dbDataEn = Nivel_en::find($id);
        DB::transaction(function() use($request, $id, $dbData, $dbDataEn){
            //Cargamos el área en español    
            $dbData->fill($request->all());
            //Cargamos el área en inglés
            $dbDataEn->descripcion = $request->descripcion_en;
            //Guardamos los registos
            $dbData->save();
            $dbDataEn->save();
        });
		return redirect()->route('niveles.index');
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $dbData = Nivel::find($id);
            $dbDataEn = Nivel_en::find($id);
            $dbData->delete();
            $dbDataEn->delete();            
            
        }catch(\Exception $e){
            DB::rollback();
            session(['delete_failed'=>"true"]);
            return redirect()->back()->withErrors($e->getMessage());
        }
        DB::commit();
		return redirect()->route('niveles.index');    	
    }
}
