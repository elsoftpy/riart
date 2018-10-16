<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Area;
use App\Area_en;
use flash;
use DB;

class AreasController extends Controller
{
    public function index(){
        $dbData = Area::get();
        // Si terminó de borrar el registro
        $toast = session('delete_failed');
        if($toast){
            session()->forget('delete_failed');
        }
    	return view('areas.list')->with('dbData', $dbData)->with('toast', $toast);
    }

    public function create(){
        return view('areas.create');
    }

    public function store(Request $request){
        DB::transaction(function() use($request){
            //Cargamos el área en español
            $dbData = new Area($request->all());
            //Cargamos el área en inglés  
            $dbDataEn = new Area_en();
            $dbDataEn->descripcion = $request->descripcion_en;
            //Guardamos los registros
            $dbData->save();
            $dbDataEn->save();            
        });

    	return redirect()->route('areas.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = Area::find($id);
       	return view('areas.edit')->with('dbData', $dbData);
    }

    public function update(Request $request, $id){
        
        //Area en español
        $dbData = Area::find($id);
        //Area en inglés
        $dbDataEn = Area_en::find($id);
        DB::transaction(function() use($request, $id, $dbData, $dbDataEn){
            //Cargamos el área en español    
            $dbData->fill($request->all());
            //Cargamos el área en inglés
            $dbDataEn->descripcion = $request->descripcion_en;
            //Guardamos los registos
            $dbData->save();
            $dbDataEn->save();
        });
        
        

		return redirect()->route('areas.index');
    }

    public function destroy($id){
        DB::beginTransaction();
        try{ 
            $dbData = Area::find($id);
            $dbDataEn = Area_en::find($id);
            $dbData->delete();
            $dbDataEn->delete();
        }catch(\Exception $e){
            DB::rollback();
            session(['delete_failed'=>"true"]);
            return redirect()->back()->withErrors($e->getMessage());
        }
        DB::commit();
        
		return redirect()->route('areas.index');    	
    }
}
