<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cargo;
use App\Cargo_en;
use App\Cargos_rubro;
use App\Area;
use App\Nivel;
use App\Rubro;
use flash;
use DB;

class CargosController extends Controller
{
    public function index(){
    	$dbData = Cargo::get();
    	return view('cargos.list')->with('dbData', $dbData);
    }

    public function create(){
		$dbNivel = Nivel::all()->pluck('descripcion', 'id');
        $dbArea = Area::all()->pluck('descripcion', 'id');
        $dbRubros = Rubro::get()->pluck('descripcion', 'id');
        return view('cargos.create')->with('dbNivel', $dbNivel)
                                    ->with('dbRubros', $dbRubros)
    								->with('dbArea', $dbArea);
    }

    public function store(Request $request){
        DB::transaction(function() use($request){
            $dbData = new Cargo($request->all());
            if(!is_null($request->is_temporal)){
                $dbData->is_temporal = 1;
            }else{
                $dbData->is_temporal = 0;
            }
               
            $dbDataEn = new Cargo_en($request->all());
            if(!is_null($request->is_temporal)){
                $dbDataEn->is_temporal = 1;
            }else{
                $dbDataEn->is_temporal = 0;
            }
            $dbDataEn->descripcion = $request->descripcion_en;
            $dbDataEn->detalle = $request->detalle_en;
            
            $dbData->save();
            $dbDataEn->save();
            
            if($request->rubros){
                foreach ($request->rubros as $key => $value) {
                    $dbRubro = new Cargos_rubro();
                    $dbRubro->cargo_id = $dbData->id;
                    $dbRubro->rubro_id = $value;
                    $dbRubro->save();
                }   
            }
            
            
        });
        
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
        $dbRubros = Rubro::get()->pluck('descripcion', 'id');
       	return view('cargos.edit')->with('dbData', $dbData)
                                  ->with('dbNivel', $dbNivel)
                                  ->with('dbRubros', $dbRubros)
                                  ->with('dbArea', $dbArea);
    }

    public function update(Request $request, $id){
        
        //Cargo en español
        $dbData = Cargo::find($id);
        //Cargo en inglés
        $dbDataEn = Cargo_en::find($id);
        DB::transaction(function() use($request, $id, $dbData, $dbDataEn){
            $dbRubros = Cargos_rubro::where('cargo_id', $id);
            
            if($request->rubros){
                if($dbRubros->count() > 0){
                    $dbRubros->delete();
                    
                }
                foreach ($request->rubros as $key => $value) {
                    $dbRubro = new Cargos_rubro();
                    $dbRubro->cargo_id = $id;
                    $dbRubro->rubro_id = $value;
                    $dbRubro->save();
                }   
            }else{
                if($dbRubros->count() > 0){
                    $dbRubros->delete();
                }
            }    
            
            $dbData->fill($request->all());
            $dbDataEn->fill($request->all());
            if(!is_null($request->is_temporal)){
                $dbData->is_temporal = 1;
                $dbDataEn->is_temporal = 1;
            }else{
                $dbData->is_temporal = 0;
                $dbDataEn->is_temporal = 0;
            }
            $dbDataEn->descripcion = $request->descripcion_en;
            $dbDataEn->detalle = $request->detalle_en;

            $dbData->save();
            $dbDataEn->save();
        });
        
        

		return redirect()->route('cargos.index');
    }

    public function destroy($id){
        $dbData = Cargo::find($id);
        $dbDataEn = Cargo_en::find($id);
        $dbData->delete();            

		return redirect()->route('cargos.index');    	
    }

    public function getDetalle(Request $request){
        if(app()->getLocale() == "en"){
            $cargo = Cargo_en::find($request->id);
        }else{
            $cargo = Cargo::find($request->id);
        }
        return $cargo->detalle;
    }


}
